import pytest
from scrapy.http import HtmlResponse
from scrapy_project.spiders.tw_stock_spider import TwStockSpider
from scrapy_project.items import StockItem
from datetime import datetime

# 模擬一個 HTTP Response
@pytest.fixture
def mock_yahoo_stock_response():
    html_content = """
    <html>
    <head><title>2330.TW - 台積電</title></head>
    <body>
        <h1 class="D(ib) Fz(24px) Mend(8px)">
            <span class="Fz(28px) Fw(b) D(ib)">台積電</span>
            <span class="Fz(24px) Mend(8px) Lh(1.2)">2330.TW</span>
        </h1>
        <div class="Fz(32px) Fw(b) Lh(1) Mend(8px) D(f) Ai(c) C(-trend-up)">620.50</div>
        <div class="D(f) Ai(c) C(-trend-up)">
            <span class="Fz(16px)">+</span>
            <span class="Fz(16px)">3.00</span>
            <span class="Fz(16px)">(0.49%)</span>
        </div>
        <!-- 更多內容 -->
    </body>
    </html>
    """
    return HtmlResponse(url="https://tw.stock.yahoo.com/quote/2330.TW", body=html_content, encoding='utf-8')

def test_parse_tw_stock_spider(mock_yahoo_stock_response, crawler):
    spider = TwStockSpider(crawler=crawler)
    results = list(spider.parse(mock_yahoo_stock_response))

    assert len(results) == 1
    item = results[0]

    assert isinstance(item, StockItem)
    assert item['symbol'] == '2330.TW'
    assert item['name'] == '台積電'
    assert item['price'] == 620.50
    assert item['change'] == 3.00 # 檢查漲跌值是否正確
    assert 'updated_at' in item
    # 驗證 updated_at 格式
    assert datetime.fromisoformat(item['updated_at'].replace('Z', '+00:00')) # 確保是有效的 ISO 格式

# TODO: 增加對 ExchangeRateSpider 和 FinancialNewsSpider 的測試骨架
# 例如：
# @pytest.fixture
# def mock_exchange_rate_response():
#     html_content = """..."""
#     return HtmlResponse(url="...", body=html_content, encoding='utf-8')
#
# def test_parse_exchange_rate_spider(mock_exchange_rate_response, crawler):
#     spider = ExchangeRateSpider(crawler=crawler)
#     results = list(spider.parse(mock_exchange_rate_response))
#     assert isinstance(results[0], ExchangeRateItem)
