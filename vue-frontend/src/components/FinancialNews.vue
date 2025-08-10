<template>
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">最新金融新聞</h2>
    <div v-if="loading" class="text-blue-500">載入中...</div>
    <div v-if="error" class="text-red-500">錯誤：${error}</div>
    <ul v-if="!loading && !error && news.length" class="space-y-4">
      <li v-for="item in news" :key="item.url" class="bg-white p-4 rounded-md shadow-sm border border-gray-200">
        <a :href="item.url" target="_blank" class="text-xl font-semibold text-blue-700 hover:underline block mb-1">
          {{ item.title }}
        </a>
        <p class="text-gray-700 text-sm mb-2">{{ item.summary }}</p>
        <div class="text-xs text-gray-500 flex justify-between">
          <span>發布時間: {{ new Date(item.published_at).toLocaleString() }}</span>
          <span>來源: <a :href="item.url" target="_blank" class="text-blue-500 hover:underline">{{ new URL(item.url).hostname }}</a></span>
        </div>
      </li>
    </ul>
    <div v-if="!loading && !error && !news.length" class="text-gray-500">目前沒有新聞數據。請稍後再試或檢查後端服務。</div>

    <div v-if="!loading && news.length && meta.last_page > 1" class="flex justify-center mt-6 space-x-2">
      <button
        @click="fetchNews(meta.current_page - 1)"
        :disabled="meta.current_page === 1"
        class="px-4 py-2 rounded-md bg-blue-500 text-white disabled:bg-gray-300 disabled:cursor-not-allowed"
      >
        上一頁
      </button>
      <span class="px-4 py-2 text-gray-700">頁數 ${meta.current_page} / ${meta.last_page}</span>
      <button
        @click="fetchNews(meta.current_page + 1)"
        :disabled="meta.current_page === meta.last_page"
        class="px-4 py-2 rounded-md bg-blue-500 text-white disabled:bg-gray-300 disabled:cursor-not-allowed"
      >
        下一頁
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import axios from 'axios'

interface FinancialNewsItem {
  title: string;
  summary: string;
  url: string;
  published_at: string;
}

interface Meta {
  current_page: number;
  from: number;
  last_page: number;
  per_page: number;
  to: number;
  total: number;
}

interface Link {
  url: string | null;
  label: string;
  active: boolean;
}

interface NewsResponse {
  data: FinancialNewsItem[];
  links: Link[];
  meta: Meta;
}

const news = ref<FinancialNewsItem[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const currentPage = ref(1)
const perPage = ref(10) # 每頁顯示數量
const meta = ref<Meta>({
  current_page: 1,
  from: 0,
  last_page: 1,
  per_page: 10,
  to: 0,
  total: 0,
})

const fetchNews = async (page: number = 1) => {
  loading.value = true;
  error.value = null;
  try {
    const res = await axios.get<NewsResponse>('/api/financial-news?page=${page}&limit=${perPage.value}')
    news.value = res.data.data
    meta.value = res.data.meta
    currentPage.value = page
  } catch (err) {
    if (axios.isAxiosError(err)) {
      error.value = err.message;
    } else {
      error.value = 'An unexpected error occurred.';
    }
    console.error('Error fetching financial news:', err)
  } finally {
    loading.value = false;
  }
}
</script>

<style scoped>
/* 自定義樣式 */
</style>
