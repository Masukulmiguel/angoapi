import { defineStore } from 'pinia'
import api from '../plugins/axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('angoapi_token') || null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
    fullName: (state) => state.user?.name || '',
  },
  actions: {
    setToken(token) {
      this.token = token
      localStorage.setItem('angoapi_token', token)
    },
    clearToken() {
      this.token = null
      localStorage.removeItem('angoapi_token')
    },
    async login(credentials) {
      const { data } = await api.post('/login', credentials)
      this.setToken(data.token)
      this.user = data.user
      return data
    },
    async register(payload) {
      const { data } = await api.post('/register', payload)
      this.setToken(data.token)
      this.user = data.user
      return data
    },
    async fetchUser() {
      try {
        const { data } = await api.get('/me')
        this.user = data.data || data
        return this.user
      } catch {
        this.clearToken()
        this.user = null
        return null
      }
    },
    async logout() {
      try {
        await api.post('/logout')
      } catch { /* silent */ }
      this.user = null
      this.clearToken()
    },
  },
})
