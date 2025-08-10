import { mount } from '@vue/test-utils'
import { describe, it, expect, vi } from 'vitest'
import StockList from '@/components/StockList.vue'
import axios from 'axios'

// 模擬 axios.get
vi.mock('axios', () => ({
  default: {
    get: vi.fn(),
  },
}))

describe('StockList.vue', () => {
  it('應正確渲染股票列表', async () => {
    // 設定模擬回應
    (axios.get as vi.Mock).mockResolvedValue({
      data: [
        { symbol: '2330.TW', name: '台積電', price: 620.50, change: 3.00, updated_at: '2025-08-10T18:00:00Z' },
        { symbol: '0050.TW', name: '元大台灣50', price: 130.00, change: -1.00, updated_at: '2025-08-10T18:00:00Z' },
      ],
    })

    const wrapper = mount(StockList)

    // 等待非同步請求完成
    await new Promise(resolve => setTimeout(resolve, 0))

    // 驗證標題
    expect(wrapper.find('h2').text()).toBe('股票列表')

    // 驗證列表項目
    const items = wrapper.findAll('li')
    expect(items.length).toBe(2)
    expect(items[0].text()).toContain('台積電 (2330.TW) : 620.50 (+3)')
    expect(items[1].text()).toContain('元大台灣50 (0050.TW) : 130.00 (-1)')

    // 驗證 loading 狀態消失
    expect(wrapper.find('.text-blue-500').exists()).toBe(false)
    // 驗證 error 狀態消失
    expect(wrapper.find('.text-red-500').exists()).toBe(false)
  })

  it('應顯示載入狀態', () => {
    // 模擬 axios 請求仍在進行中
    (axios.get as vi.Mock).mockImplementation(() => new Promise(() => {}))

    const wrapper = mount(StockList)
    expect(wrapper.find('.text-blue-500').text()).toBe('載入中...')
  })

  it('應顯示錯誤訊息', async () => {
    // 模擬 axios 請求失敗
    (axios.get as vi.Mock).mockRejectedValue(new Error('Network Error'))

    const wrapper = mount(StockList)

    // 等待非同步請求完成
    await new Promise(resolve => setTimeout(resolve, 0))

    expect(wrapper.find('.text-red-500').text()).toContain('錯誤：Network Error')
  })

  it('應顯示無數據訊息', async () => {
    // 模擬 axios 請求返回空數據
    (axios.get as vi.Mock).mockResolvedValue({ data: [] })

    const wrapper = mount(StockList)

    // 等待非同步請求完成
    await new Promise(resolve => setTimeout(resolve, 0))

    expect(wrapper.text()).toContain('目前沒有股票數據。請稍後再試或檢查後端服務。')
  })
})
