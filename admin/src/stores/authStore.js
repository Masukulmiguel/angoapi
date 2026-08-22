import { create } from 'zustand'
import { persist } from 'zustand/middleware'
import api from '../lib/axios'

export const useAuthStore = create(
  persist(
    (set) => ({
      user: null,
      token: null,
      loading: false,
      error: null,
      login: async (email, password) => {
        set({ loading: true, error: null })
        try {
          const res = await api.post('/admin/login', { email, password })
          const { user, token } = res.data.data
          set({ user, token, loading: false })
          return true
        } catch (err) {
          set({ error: err.response?.data?.message || 'Login failed', loading: false })
          return false
        }
      },
      logout: () => set({ user: null, token: null }),
      clearError: () => set({ error: null }),
    }),
    { name: 'auth-storage' }
  )
)
