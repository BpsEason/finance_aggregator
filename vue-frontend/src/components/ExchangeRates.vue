<template>
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">匯率列表</h2>
    <div v-if="loading" class="text-blue-500">載入中...</div>
    <div v-if="error" class="text-red-500">錯誤：${error}</div>
    <ul v-if="!loading && !error && exchangeRates.length" class="space-y-2">
      <li v-for="rate in exchangeRates" :key="rate.currency" class="bg-gray-100 p-3 rounded-md shadow-sm flex justify-between items-center">
        <span class="font-semibold text-lg">{{ rate.currency }}</span> : <span class="font-bold text-xl text-indigo-700">{{ rate.rate }}</span>
        <div class="text-xs text-gray-500">更新於: {{ new Date(rate.updated_at).toLocaleString() }}</div>
      </li>
    </ul>
    <div v-if="!loading && !error && !exchangeRates.length" class="text-gray-500">目前沒有匯率數據。請稍後再試或檢查後端服務。</div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import axios from 'axios'

interface ExchangeRate {
  currency: string;
  rate: number;
  updated_at: string;
}

const exchangeRates = ref<ExchangeRate[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const res = await axios.get<ExchangeRate[]>('/api/exchange-rates')
    exchangeRates.value = res.data
  } catch (err) {
    if (axios.isAxiosError(err)) {
      error.value = err.message;
    } else {
      error.value = 'An unexpected error occurred.';
    }
    console.error('Error fetching exchange rates:', err)
  } finally {
    loading.value = false;
  }
})
</script>

<style scoped>
/* 自定義樣式 */
</style>
