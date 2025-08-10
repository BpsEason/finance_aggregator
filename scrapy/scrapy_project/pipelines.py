import pymysql
from itemadapter import ItemAdapter
import logging
from twisted.enterprise import adbapi
import os # 導入 os 模組

# 導入定義好的 Item 類別
from scrapy_project.items import StockItem, ExchangeRateItem, FinancialNewsItem

class DatabasePipeline:
    def __init__(self, db_settings):
        self.db_settings = db_settings
        self.dbpool = None
        self.logger = logging.getLogger(__name__)

    @classmethod
    def from_crawler(cls, crawler):
        # 從設定中取得資料庫參數
        db_settings = {
            'host': crawler.settings.get('MYSQL_HOST', 'db'),
            'port': crawler.settings.getint('MYSQL_PORT', 3306),
            'database': crawler.settings.get('MYSQL_DATABASE', 'laravel_finance'),
            'user': crawler.settings.get('MYSQL_USER', 'laravel'),
            'password': crawler.settings.get('MYSQL_PASSWORD', 'secret'),
            'charset': 'utf8mb4',
            'cursorclass': pymysql.cursors.DictCursor
        }
        return cls(db_settings)

    def open_spider(self, spider):
        # 在 Spider 開始時建立資料庫連接池
        self.dbpool = adbapi.ConnectionPool('pymysql', **self.db_settings)
        self.logger.info("Database connection pool opened.")

    def close_spider(self, spider):
        # 在 Spider 結束時關閉資料庫連接池
        if self.dbpool:
            self.dbpool.close()
            self.logger.info("Database connection pool closed.")

    def process_item(self, item, spider):
        # 將每個 Item 異步地寫入資料庫
        adapter = ItemAdapter(item)
        if isinstance(item, StockItem):
            self.dbpool.runInteraction(self._upsert_stock, adapter)
        elif isinstance(item, ExchangeRateItem):
            self.dbpool.runInteraction(self._upsert_exchange_rate, adapter)
        elif isinstance(item, FinancialNewsItem):
            self.dbpool.runInteraction(self._upsert_financial_news, adapter)
        return item

    def _upsert_stock(self, cursor, item):
        # UPSERT 股票資料
        sql = """
            INSERT INTO stocks (symbol, name, price, , updated_at)
            VALUES (%s, %s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                price = VALUES(price),
                 = VALUES(),
                updated_at = VALUES(updated_at);
        """
        try:
            cursor.execute(sql, (item['symbol'], item['name'], item['price'], item['change'], item['updated_at']))
            self.logger.debug(f"UPSERT Stock: {item['symbol']}")

            # 插入或更新股票歷史資料 (如果需要)
            stock_id_sql = "SELECT id FROM stocks WHERE symbol = %s;"
            cursor.execute(stock_id_sql, (item['symbol'],))
            stock_id = cursor.fetchone()['id']

            history_sql = """
                INSERT INTO stock_histories (stock_id, date, price, created_at, updated_at)
                VALUES (%s, %s, %s, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                    price = VALUES(price),
                    updated_at = NOW();
            """
            # 歷史日期通常是爬取當天的日期
            today_date = item['updated_at'].split('T')[0] if 'T' in item['updated_at'] else item['updated_at'].split(' ')[0]
            cursor.execute(history_sql, (stock_id, today_date, item['price']))
            self.logger.debug(f"UPSERT Stock History for {item['symbol']} on {today_date}")
        except Exception as e:
            self.logger.error(f"Error UPSERTING Stock {item['symbol']}: {e}")
            # 可以選擇在此處拋出異常，讓 Scrapy 處理重試，或者只是記錄錯誤

    def _upsert_exchange_rate(self, cursor, item):
        # UPSERT 匯率資料
        sql = """
            INSERT INTO exchange_rates (currency, rate, updated_at)
            VALUES (%s, %s, %s)
            ON DUPLICATE KEY UPDATE
                rate = VALUES(rate),
                updated_at = VALUES(updated_at);
        """
        try:
            cursor.execute(sql, (item['currency'], item['rate'], item['updated_at']))
            self.logger.debug(f"UPSERT Exchange Rate: {item['currency']}")
        except Exception as e:
            self.logger.error(f"Error UPSERTING Exchange Rate {item['currency']}: {e}")

    def _upsert_financial_news(self, cursor, item):
        # UPSERT 金融新聞資料
        sql = """
            INSERT INTO financial_news (title, summary, url, published_at, updated_at)
            VALUES (%s, %s, %s, %s, %s)
            ON DUPLICATE KEY UPDATE
                title = VALUES(title),
                summary = VALUES(summary),
                published_at = VALUES(published_at),
                updated_at = VALUES(updated_at);
        """
        try:
            cursor.execute(sql, (item['title'], item['summary'], item['url'], item['published_at'], item['updated_at']))
            self.logger.debug(f"UPSERT Financial News: {item['title']}")
        except Exception as e:
            self.logger.error(f"Error UPSERTING Financial News {item['title']}: {e}")

