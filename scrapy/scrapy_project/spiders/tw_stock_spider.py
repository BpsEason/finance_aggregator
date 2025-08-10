import scrapy
from datetime import datetime
from ..items import StockItem # 導入定義好的 Item

class TwStockSpider(scrapy.Spider):
    name = 'tw_stock_spider' # Spider 的名稱
    # 這裡可以放置爬取台股的起始 URL，例如 Yahoo 股市或其他公開資訊觀測站
    # 注意：實際的爬取需要解析網站結構，這裡僅作骨架
    start_urls = [
        'https://tw.stock.yahoo.com/quote/2330.TW', # 台積電
        'https://tw.stock.yahoo.com/quote/0050.TW', # 元大台灣50
        # 更多股票代碼...
    ]

    def parse(self, response):
        # 解析股票頁面，提取資料
        # 這裡的 XPath/CSS Selector 僅為範例，實際需根據網站結構調整

        # 範例：從 Yahoo 股市頁面提取資料
        # 為了簡化，直接從 URL 獲取代碼。實際可能需要從頁面內容解析。
        symbol = response.url.split('/')[-1].replace('.html', '').replace('/', '')
        if '.TW' not in symbol and '.US' not in symbol: # 簡單判斷，避免獲取到非代碼部分
             symbol = 'UNKNOWN_STOCK' # 或跳過此頁面

        # 假設股票名稱在 H1 標籤內
        name = response.xpath('//h1[contains(@class, "D(ib)")]/span[1]/text()').get()
        # 假設價格在特定的 div 標籤中
        price_str = response.xpath('//div[contains(@class, "Fz(32px)")]/text()').get()
        # 假設漲跌在另一個 div 標籤中
        change_element = response.xpath('//div[contains(@class, "D(f) Ai(c)")]')
        change_str = change_element.xpath('./span[2]/text()').get()
        change_sign = change_element.xpath('./span[1]/text()').get() # 獲取 '+' 或 '-'

        # 清理和轉換資料
        price = float(price_str.replace(',', '')) if price_str else None
        change = float(change_str) if change_str else None
        if change is not None and change_sign == '-':
            change *= -1 # 如果是跌，則為負數

        # 確保 symbol 和 name 不為空
        if not symbol or not name:
            self.logger.warning(f"Skipping item due to missing symbol or name from {response.url}")
            return # 跳過此 item

        item = StockItem()
        item['symbol'] = symbol.strip()
        item['name'] = name.strip()
        item['price'] = price
        item['change'] = change
        item['updated_at'] = datetime.utcnow().isoformat(timespec='seconds') + 'Z' # 使用 UTC 時間，精確到秒

        yield item

        # 如果有多個股票頁面需要跟隨連結，可以在這裡使用 response.follow
        # 例如：response.follow(next_page_url, self.parse)
