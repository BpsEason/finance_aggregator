<template>
  <div class="about-page">
    <h1 class="text-3xl font-bold mb-4">關於本平台</h1>
    <p class="text-lg text-gray-700">
      本金融資訊聚合平台旨在提供使用者一個方便、高效的方式，獲取各種即時金融數據。
      透過 Scrapy 爬蟲定期抓取資料，並由 Laravel 後端提供穩定的 API 服務，前端則採用 Vue 3 呈現互動式介面。
    </p>
    <p class="text-md text-gray-600 mt-2">
      我們致力於提供高 QPS 的效能、良好的可維護性，並採用 Docker 進行服務容器化，GitHub Actions 進行 CI/CD。
    </p>
  </div>
</template>

<style scoped>
.about-page {
  text-align: center;
  padding: 2rem;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
