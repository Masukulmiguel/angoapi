import { defineStore } from 'pinia'
import api from '../plugins/axios'

export const usePlansStore = defineStore('plans', {
  state: () => ({
    plans: [],
    loading: false,
  }),
  actions: {
    async fetchPlans() {
      this.loading = true
      try {
        const { data } = await api.get('/plans')
        this.plans = data.data || data
      } catch (e) {
        console.error('Failed to fetch plans', e)
      } finally {
        this.loading = false
      }
    },
  },
})
