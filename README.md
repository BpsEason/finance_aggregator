# Finance Aggregator 金融資訊聚合平台

Finance Aggregator 是一套端到端的金融數據聚合解決方案，涵蓋自動爬取、集中存儲、API 服務與互動式前端展示。只需 Docker Compose 一鍵部署，即可獲取並可視化台股、國際股價、匯率與新聞數據。

---

## 亮點

- 完整容器化部署：MySQL、Laravel API、Scrapy 爬蟲、Vue 3 前端全由 Docker Compose 管理。  
- 多源資料聚合：定時抓取台股、全球匯率與金融新聞，採用 UPSERT 機制避免重複。  
- 高效 RESTful API：Laravel 10 + PHP 8.2 提供 Stocks、ExchangeRates、News 三大端點。  
- 互動式儀表板：Vue 3 + Vite + Pinia 單頁應用，支援列表、詳情與分頁導航。  
- 全面測試覆蓋：PHPUnit、PyTest、Vitest 與 Cypress，保證後端、爬蟲與前端品質。  
- 自動化 CI/CD：GitHub Actions 執行測試、Lint、映像建置，並可根據分支自動部署。

---

## 關鍵技術

| 服務／模組         | 技術棧                         | 角色說明                                    |
|--------------------|------------------------------|---------------------------------------------|
| crawler            | Python 3.11 + Scrapy         | 分散式爬蟲：股票、匯率、新聞資料抓取並 UPSERT      |
| db                 | MySQL 8                      | 關聯式存儲：支援歷史資料、索引優化               |
| backend (API)      | Laravel 10 + PHP 8.2         | RESTful API、後台 Dashboard                   |
| frontend (SPA)     | Vue 3 + Vite + Pinia         | 單頁應用：互動式列表、詳細頁與分頁               |
| orchestrator       | Docker + Docker Compose      | 容器化啟動與網路管理                         |
| ci/cd              | GitHub Actions               | 自動化測試、掃描、映像建置與可選部署             |

---

## 系統架構

       ┌────────────┐      ┌───────────────┐      ┌────────────┐      ┌──────────────┐  
       │   Scrapy   │ ───▶ │    MySQL      │ ◀─── │  Laravel   │ ───▶ │   Vue 3      │  
       │ (Crawler)  │      │ (finance_db)  │      │ (API +     │      │ (Vite SPA)   │  
       │ Python 3.11│      │               │      │ Dashboard) │      │              │  
       └────────────┘      └───────────────┘      └────────────┘      └──────────────┘  
             ▲                                                               │  
             │                                                               │  
             └────────Cron / Laravel Scheduler───────────┘  

---

## 功能一覽

1. 自動爬取  
   - 台股（tw_stock_spider）  
   - 匯率（exchange_rate_spider）  
   - 新聞（financial_news_spider）  
2. UPSERT 機制  
   - 避免重複插入  
   - 非同步連線池高效寫入  
3. RESTful API  
   - `GET  /api/stocks`  
   - `GET  /api/stocks/{symbol}`  
   - `GET  /api/exchange-rates`  
   - `GET  /api/financial-news?page=&limit=`  
   - `POST /api/tests/trigger-crawl`（手動觸發爬蟲）  
4. 前端互動  
   - 股票列表與詳細  
   - 匯率一覽  
   - 分頁新聞與來源連結  
5. 後台儀表板  
   - Blade 模板快速自訂  
   - Laravel Scheduler 排程管理  

---

## 快速上手

### 先決條件

- Docker & Docker Compose ≥ 1.29  
- （開發選項）Node.js ≥ 18、npm 或 yarn  

### 環境變數

根目錄含 `.env.example`、`scrapy/.env.example`、`vue-frontend/.env.example`。複製並依需求修改：

    cp .env.example .env
    cp scrapy/.env.example scrapy/.env
    cp vue-frontend/.env.example vue-frontend/.env

### 一鍵部署

    docker compose up -d --build

容器健康後，執行依賴安裝與資料庫遷移：

    # Laravel
    docker compose exec app composer install
    docker compose exec app php artisan key:generate
    docker compose exec app php artisan migrate

    # Vue 前端
    docker compose exec vue_frontend npm install

（可選）手動觸發爬蟲：

    docker compose exec scrapy scrapy crawl tw_stock_spider

---

## 使用方式

- Vue SPA： http://localhost:5173  
- Laravel API： http://localhost:8000/api  
- Scrapyweb UI： http://localhost:6800  

---

## 測試

### Laravel（PHPUnit）

    docker compose exec app php artisan test

### Scrapy（PyTest）

    docker compose exec scrapy pytest

### Vue 前端

- 單元測試（Vitest）：

      docker compose exec vue_frontend npm run test:unit

- E2E 測試（Cypress）：

      docker compose exec vue_frontend npm run test:e2e:dev

---

## CI/CD

GitHub Actions 工作流程自動完成：  
1. 安裝 PHP、Python、Node.js 環境  
2. 啟動 MySQL 容器  
3. Laravel 測試與遷移  
4. Scrapy 測試  
5. Vue 單元與 E2E 測試  
6. Docker 映像建置與標記  
7. （可選）依分支部署至 Staging/Production  

---

## 貢獻

歡迎開 issue 或 PR，請依下列步驟：  
1. Fork 本 Repo  
2. 建立 feature 分支  
3. 完成後提交 PR  
4. CI 通過後合併  

---

## 授權

本專案採用 MIT License，詳見 LICENSE。
