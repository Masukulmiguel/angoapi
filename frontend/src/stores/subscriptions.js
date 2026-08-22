import { defineStore } from 'pinia'
import api from '../plugins/axios'

export const useSubscriptionsStore = defineStore('subscriptions', {
  state: () => ({
    current: null,
    list: [],
    loading: false,
  }),
  actions: {
    async fetchCurrent() {
      this.loading = true
      try {
        const { data } = await api.get('/subscriptions/current')
        this.current = data.data || data
      } catch { this.current = null } finally { this.loading = false }
    },
    async fetchList() {
      try {
        const { data } = await api.get('/subscriptions')
        this.list = data.data || data
      } catch { this.list = [] }
    },
  },
})
