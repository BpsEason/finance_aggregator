// src/store/index.js
import { defineStore } from 'pinia'

export const useAppStore = defineStore('app', {
  state: () => ({
    loading: false,
    error: null as string | null,
  }),
  actions: {
    setLoading(isLoading: boolean) {
      this.loading = isLoading
    },
    setError(errorMessage: string | null) {
      this.error = errorMessage
    }
  },
  getters: {
    isLoading: (state) => state.loading,
    getErrorMessage: (state) => state.error,
  }
})
