<template>
  <div class="min-h-screen flex bg-gray-50 dark:bg-gray-950">
    <Sidebar :collapsed="sidebarCollapsed" @toggle="sidebarCollapsed = !sidebarCollapsed" />
    <div class="flex-1 flex flex-col min-h-screen transition-all duration-300" :class="sidebarCollapsed ? 'ml-16' : 'ml-64'">
      <header class="h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center px-6 sticky top-0 z-30">
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="mr-4 text-gray-500 dark:text-gray-400 hover:text-ango-navy dark:hover:text-white lg:hidden">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <div class="flex-1" />
        <ThemeToggle />
        <div class="flex items-center gap-4 ml-4">
          <span class="text-sm text-gray-600 dark:text-gray-300">{{ auth.fullName }}</span>
          <div class="w-8 h-8 rounded-full bg-ango-navy text-white flex items-center justify-center text-sm font-semibold">
            {{ initials }}
          </div>
        </div>
      </header>
      <main class="flex-1 p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import Sidebar from '../components/Sidebar.vue'
import ThemeToggle from '../components/ThemeToggle.vue'

const auth = useAuthStore()
const sidebarCollapsed = ref(false)

const initials = computed(() => {
  if (!auth.user?.name) return '?'
  return auth.user.name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2)
})

onMounted(async () => {
  if (auth.isAuthenticated && !auth.user) {
    await auth.fetchUser()
  }
})
</script>
