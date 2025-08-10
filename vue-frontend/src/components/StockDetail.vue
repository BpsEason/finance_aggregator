<template>
  <div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">股票詳細資料: ${symbol}</h2>
    <div v-if="loading" class="text-blue-500">載入中...</div>
    <div v-if="error" class="text-red-500">錯誤：${error}</div>

    <div v-if="!loading && stock">
      <h3 class="text-xl font-semibold">{{ stock.name }} ({{ stock.symbol }})</h3>
      <p>目前價格: <span :class="{'text-green-700': stock.change >= 0, 'text-red-700': stock.change < 0}" class="font-bold text-lg">{{ stock.price }}</span></p>
      <p>漲跌: <span :class="{'text-green-600': stock.change >= 0, 'text-red-600': stock.change < 0}">{{ stock.change > 0 ? '+' : '' }}${stock.change}</span></p>
      <p class="text-sm text-gray-500">更新時間: {{ new Date(stock.updated_at).toLocaleString() }}</p>

      <h4 class="text-xl font-bold mt-6 mb-3">歷史價格</h4>
      <div v-if="stock.history && stock.history.length">
        <ul class="list-disc pl-5">
          <li v-for="data in stock.history" :key="data.date" class="text-gray-700">
            {{ data.date }}: {{ data.price }}
          </li>
        </ul>
        <!-- 這裡可以整合 Chart.js 或其他圖表庫來顯示圖表 -->
        <p class="text-sm text-gray-500 mt-4"> (此處可渲染歷史價格圖表)</p>
      </div>
      <div v-else class="text-gray-500">無歷史價格資料。</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

interface StockDetailData {
  symbol: string;
  name: string;
  price: number;
  change: number;
  updated_at: string;
  history: Array<{ date: string; price: number; }>;
}

const route = useRoute()
const stock = ref<StockDetailData | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)
const symbol = ref<string>(route.params.symbol as string)

const fetchStockDetail = async (currentSymbol: string) => {
  loading.value = true;
  error.value = null;
  try {
    const res = await axios.get<StockDetailData>('/api/stocks/${currentSymbol}')
    stock.value = res.data
  } catch (err) {
    if (axios.isAxiosError(err)) {
      if (err.response && err.response.status === 404) {
        error.value = '找不到該股票資料。';
      } else {
        error.value = err.message;
      }
    } else {
      error.value = 'An unexpected error occurred.';
    }
    console.error('Error fetching stock detail:', err)
    stock.value = null; # 清空資料以避免顯示舊資料
  } finally {
    loading.value = false;
  }
}

onMounted(() => {
  fetchStockDetail(symbol.value)
})

watch(() => route.params.symbol, (newSymbol) => {
  symbol.value = newSymbol as string;
  fetchStockDetail(newSymbol as string);
})
</script>

<style scoped>
/* 自定義樣式 */
</style>
