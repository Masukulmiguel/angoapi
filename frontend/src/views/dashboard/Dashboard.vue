<template>
  <div class="space-y-6 dark:bg-gray-900 dark:text-white">
    <div>
      <h1 class="text-2xl font-bold text-ango-navy dark:text-white">Visão Geral</h1>
      <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Bem-vindo de volta, {{ auth.fullName }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="stat in stats" :key="stat.label" class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-100 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-3">
          <span class="text-sm text-gray-500 dark:text-gray-400">{{ stat.label }}</span>
          <div class="w-9 h-9 rounded-lg flex items-center justify-center" :class="stat.bg">{{ stat.icon }}</div>
        </div>
        <p class="text-2xl font-bold text-ango-navy dark:text-white">{{ stat.value }}</p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ stat.sub }}</p>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <h3 class="font-semibold text-ango-navy dark:text-white mb-4">Subscrição Actual</h3>
        <div v-if="subStore.current" class="space-y-3">
          <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Plano</span>
            <span class="font-medium text-ango-navy dark:text-white">{{ subStore.current.plan?.name || 'N/A' }}</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Estado</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Activo</span>
          </div>
          <div class="flex justify-between text-sm">
            <span class="text-gray-500 dark:text-gray-400">Expira em</span>
            <span class="font-medium text-ango-navy dark:text-white">{{ subStore.current.ends_at || 'N/A' }}</span>
          </div>
        </div>
        <div v-else class="text-center py-6">
          <p class="text-gray-400 dark:text-gray-500 text-sm mb-3">Sem subscrição activa</p>
          <router-link to="/planos" class="text-sm text-ango-red font-semibold hover:underline">Ver planos →</router-link>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6">
        <h3 class="font-semibold text-ango-navy dark:text-white mb-4">Actividade Recente</h3>
        <div class="space-y-3">
          <div v-for="(log, i) in recentLogs" :key="i" class="flex items-center gap-3 text-sm">
            <div class="w-2 h-2 rounded-full" :class="log.color" />
            <span class="text-gray-600 dark:text-gray-300 flex-1">{{ log.text }}</span>
            <span class="text-gray-400 dark:text-gray-500 text-xs">{{ log.time }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { useSubscriptionsStore } from '../../stores/subscriptions'

const auth = useAuthStore()
const subStore = useSubscriptionsStore()

const stats = ref([
  { label: 'Requisições hoje', value: '0', sub: 'de 5.000 disponível', icon: '📊', bg: 'bg-blue-50' },
  { label: 'API Keys', value: '0', sub: 'activas', icon: '🔑', bg: 'bg-purple-50' },
  { label: 'Subscrição', value: 'N/A', sub: 'sem plano activo', icon: '💳', bg: 'bg-green-50' },
  { label: 'Pagamentos', value: '0', sub: 'este mês', icon: '💰', bg: 'bg-orange-50' },
])

const recentLogs = ref([
  { text: 'Login realizado', time: 'Agora', color: 'bg-green-400' },
  { text: 'Conta criada', time: 'Hoje', color: 'bg-blue-400' },
])

onMounted(() => {
  subStore.fetchCurrent()
})
</script>
