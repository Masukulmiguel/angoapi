import { create } from 'zustand'
import api from '../lib/axios'

export const useDashboardStore = create((set) => ({
  stats: null,
  charts: null,
  loading: false,
  error: null,

  fetchDashboard: async () => {
    set({ loading: true, error: null })
    try {
      const res = await api.get('/admin/dashboard')
      set({ stats: res.data.data.stats, charts: res.data.data.charts, loading: false })
    } catch (err) {
      set({ error: err.response?.data?.message || 'Failed to load dashboard', loading: false })
    }
  },

  fetchStats: async () => {
    try {
      const res = await api.get('/admin/dashboard/stats')
      set({ stats: res.data.data })
    } catch (err) {
      console.error(err)
    }
  },

  fetchCharts: async () => {
    try {
      const res = await api.get('/admin/dashboard/charts')
      set({ charts: res.data.data })
    } catch (err) {
      console.error(err)
    }
  },
}))
