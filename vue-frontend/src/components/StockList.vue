<template>
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">股票列表</h2>
    <div v-if="loading" class="text-blue-500">載入中...</div>
    <div v-if="error" class="text-red-500">錯誤：${error}</div>
    <ul v-if="!loading && !error && stocks.length" class="space-y-2">
      <li v-for="stock in stocks" :key="stock.symbol" class="bg-gray-100 p-3 rounded-md shadow-sm flex justify-between items-center">
        <div>
          <span class="font-semibold text-lg">{{ stock.name }} ({{ stock.symbol }})</span>
          <br>
          <span :class="{'text-green-700': stock.change >= 0, 'text-red-700': stock.change < 0}" class="font-bold text-xl">{{ stock.price }}</span>
          <span :class="{'text-green-600': stock.change >= 0, 'text-red-600': stock.change < 0}" class="ml-2 text-sm">({{ stock.change > 0 ? '+' : '' }}${stock.change})</span>
        </div>
        <div class="text-xs text-gray-500">更新於: {{ new Date(stock.updated_at).toLocaleString() }}</div>
      </li>
    </ul>
    <div v-if="!loading && !error && !stocks.length" class="text-gray-500">目前沒有股票數據。請稍後再試或檢查後端服務。</div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import axios from 'axios'

interface Stock {
  symbol: string;
  name: string;
  price: number;
  change: number;
  updated_at: string;
}

const stocks = ref<Stock[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const res = await axios.get<Stock[]>('/api/stocks')
    stocks.value = res.data
  } catch (err) {
    if (axios.isAxiosError(err)) {
      error.value = err.message;
    } else {
      error.value = 'An unexpected error occurred.';
    }
    console.error('Error fetching stocks:', err)
  } finally {
    loading.value = false;
  }
})
</script>

<style scoped>
/* 可以放置 Tailwind 以外的自定義樣式 */
</style>
