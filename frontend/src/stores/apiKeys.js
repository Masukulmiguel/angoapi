import { defineStore } from 'pinia'
import api from '../plugins/axios'

export const useApiKeysStore = defineStore('apiKeys', {
  state: () => ({
    list: [],
    loading: false,
  }),
  actions: {
    async fetchList() {
      this.loading = true
      try {
        const { data } = await api.get('/api-keys')
        this.list = data.data || data
      } catch { this.list = [] } finally { this.loading = false }
    },
    async createKey(payload) {
      const { data } = await api.post('/api-keys', payload)
      await this.fetchList()
      return data
    },
    async revokeKey(id) {
      await api.delete(`/api-keys/${id}`)
      await this.fetchList()
    },
    async regenerateKey(id) {
      const { data } = await api.post(`/api-keys/${id}/regenerate`)
      await this.fetchList()
      return data
    },
  },
})
