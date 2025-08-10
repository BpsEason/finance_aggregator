# ./scrapy/Dockerfile
FROM python:3.11-slim-buster

# 設定工作目錄
WORKDIR /app

# 安裝 Scrapy 和其他必要的 Python 套件
COPY ./requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt \
    && pip install mysqlclient # Scrapy 連接 MySQL 需要此套件

# 複製 Scrapy 專案檔案
COPY . .

# Scrapy 預設執行命令 (這裡我們假設會使用 Scrapyweb 來管理)
# CMD ["scrapy", "crawlall"] # 如果要自動啟動所有爬蟲，但通常會透過 Scrapyweb 或 Laravel 觸發
# 啟動 Scrapyweb 服務，讓 Laravel 可以透過 HTTP API 觸發爬蟲
CMD ["scrapyweb", "runserver", "0.0.0.0:6800"]
