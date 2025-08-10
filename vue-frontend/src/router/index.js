import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../pages/Home.vue'
import StockListView from '../components/StockList.vue'
import ExchangeRatesView from '../components/ExchangeRates.vue'
import FinancialNewsView from '../components/FinancialNews.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/stocks',
      name: 'stocks',
      component: StockListView
    },
    {
      path: '/exchange-rates',
      name: 'exchange-rates',
      component: ExchangeRatesView
    },
    {
      path: '/news',
      name: 'news',
      component: FinancialNewsView
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (AboutView.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../pages/AboutView.vue')
    }
  ]
})

export default router
