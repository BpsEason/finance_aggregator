# Finance Aggregator 金融資訊聚合平台

此專案旨在建立一個自動爬取、儲存並提供台股、國際股價、匯率、指數與新聞等金融資訊的平台。

## 系統架構

```
       ┌────────────┐      ┌───────────────┐      ┌────────────┐      ┌──────────────┐
       │   Scrapy   │ ───▶ │    MySQL      │ ◀─── │  Laravel   │ ───▶ │   Vue 3      │
       │ (Crawler)  │      │ (laravel_db)  │      │ (API +     │      │  (Vite SPA)  │
       │ Python 3.11│      │               │      │ Dashboard) │      │              │
       └────────────┘      └───────────────┘      └────────────┘      └──────────────┘
             ▲                                                               │
             │                                                               │
             └────────────Cron / Laravel Scheduler───────────────┘
```

## 服務清單

| 服務名稱       | 技術棧                  | 角色                       |
| -------------- | ----------------------- | -------------------------- |
| `crawler`    | Python 3.11 + Scrapy    | 爬取股票、匯率、新聞資料並 upsert |
| `db`         | MySQL 8                 | 儲存所有財經資料           |
| `backend (API)` | Laravel 10 + PHP 8.1    | 提供 RESTful API、後台儀表板 |
| `frontend (SPA)`| Vue 3 + Vite + Pinia    | 單頁應用程式，串接 API 顯示圖表 |
| `orchestrator` | Docker + Docker Compose | 管理容器啟動與網路         |
| `ci/cd`      | GitHub Actions          | 自動化測試、掃描、建置與部署 |

## 啟動專案

1.  **複製環境變數檔案**：

    ```bash
    cp .env.example .env
    ```

    如果需要，請編輯 `.env` 檔案中的資料庫憑證或其他設定。

2.  **啟動 Docker 服務**：
    在專案根目錄下執行 (此命令會自動建置 Docker 映像檔)：

    ```bash
    docker compose up -d --build
    ```

3.  **安裝 Laravel 相依性**：
    等待 `db` 和 `app` 服務啟動並健康後，進入 `app` 容器執行 Composer 安裝：

    ```bash
    docker compose exec app composer install
    ```
    接著，生成應用程式金鑰：
    ```bash
    docker compose exec app php artisan key:generate
    ```

4.  **執行 Laravel 資料庫遷移 (Migration)**：

    ```bash
    docker compose exec app php artisan migrate
    ```

5.  **安裝 Vue 前端相依性**：
    進入 `vue_frontend` 容器執行 npm 安裝：

    ```bash
    docker compose exec vue_frontend npm install
    ```

6.  **手動觸發 Scrapy 爬蟲 (可選)**：
    等待 `scrapy` 服務啟動後，可以手動觸發一個爬蟲來填充資料：

    ```bash
    docker compose exec scrapy scrapy crawl tw_stock_spider
    ```
    (注意：這需要您先在 Scrapy 專案中實現 `tw_stock_spider`)

7.  **訪問應用程式**：
    * **Vue 前端 (SPA)**：`http://localhost:5173`
    * **Laravel 後端 API**：`http://localhost:8000/api`
    * **Scrapyweb UI**：`http://localhost:6800` (用於監控和手動觸發爬蟲)

## 開發

### Laravel 後端

進入 `laravel/` 目錄進行開發。API 端點定義在 `routes/api.php`，儀表板頁面在 `resources/views/dashboard.blade.php`。

### Vue 前端

進入 `vue-frontend/` 目錄進行開發。開發伺服器會自動熱重載。

### Scrapy 爬蟲

進入 `scrapy/` 目錄進行開發。爬蟲定義在 `scrapy_project/spiders/`。

## 測試

### Laravel 測試

在 `laravel/` 目錄下執行 PHPUnit：

```bash
docker compose exec app php artisan test
```

### Scrapy 測試

在 `scrapy/` 目錄下執行 pytest：

```bash
docker compose exec scrapy pytest
```

### Vue 前端測試

在 `vue-frontend/` 目錄下執行 Vitest (單元測試) 或 Cypress (E2E 測試)：

```bash
docker compose exec vue_frontend npm run test:unit
docker compose exec vue_frontend npm run test:e2e # 或 test:e2e:dev
```

## CI/CD (GitHub Actions)

專案根目錄下的 `.github/workflows/ci.yml` 定義了 CI/CD 流程，包含自動化測試、建置 Docker 映像檔等。
