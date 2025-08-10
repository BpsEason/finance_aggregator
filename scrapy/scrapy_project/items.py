import scrapy

class StockItem(scrapy.Item):
    # 定義股票的欄位
    symbol = scrapy.Field() # 股票代碼 (例如: 2330.TW)
    name = scrapy.Field()   # 股票名稱 (例如: 台積電)
    price = scrapy.Field()  # 即時價格
    change = scrapy.Field() # 漲跌金額
    updated_at = scrapy.Field() # 更新時間 (ISO 格式)

class ExchangeRateItem(scrapy.Item):
    # 定義匯率的欄位
    currency = scrapy.Field() # 貨幣對 (例如: USD/TWD)
    rate = scrapy.Field()     # 匯率
    updated_at = scrapy.Field() # 更新時間 (ISO 格式)

class FinancialNewsItem(scrapy.Item):
    # 定義金融新聞的欄位
    title = scrapy.Field()       # 新聞標題
    summary = scrapy.Field()     # 新聞摘要
    url = scrapy.Field()         # 新聞連結
    published_at = scrapy.Field() # 發布時間 (ISO 格式)
