import { defineStore } from 'pinia'
import api from '../plugins/axios'

export const usePaymentsStore = defineStore('payments', {
  state: () => ({
    list: [],
    loading: false,
  }),
  actions: {
    async fetchList() {
      this.loading = true
      try {
        const { data } = await api.get('/payments')
        this.list = data.data || data
      } catch { this.list = [] } finally { this.loading = false }
    },
    async storePayment(payload) {
      const { data } = await api.post('/payments', payload)
      return data
    },
  },
})
