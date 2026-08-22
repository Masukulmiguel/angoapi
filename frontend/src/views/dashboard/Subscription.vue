<template>
  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-bold text-ango-navy">Subscrição</h1>
      <p class="text-gray-500 text-sm mt-1">Gerir o seu plano actual</p>
    </div>

    <div v-if="subStore.current" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h3 class="text-lg font-bold text-ango-navy">{{ subStore.current.plan?.name || 'Plano Actual' }}</h3>
          <p class="text-sm text-gray-500">{{ subStore.current.plan?.price || '' }} Kz/ano</p>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">ACTIVO</span>
      </div>
      <div class="grid sm:grid-cols-3 gap-4 text-sm">
        <div class="p-4 bg-gray-50 rounded-lg">
          <p class="text-gray-400 mb-1">Início</p>
          <p class="font-semibold text-ango-navy">{{ subStore.current.starts_at || 'N/A' }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <p class="text-gray-400 mb-1">Expira</p>
          <p class="font-semibold text-ango-navy">{{ subStore.current.ends_at || 'N/A' }}</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
          <p class="text-gray-400 mb-1">Requisições</p>
          <p class="font-semibold text-ango-navy">{{ subStore.current.requests_used || 0 }} / {{ subStore.current.requests_limit || '∞' }}</p>
        </div>
      </div>
    </div>

    <div v-else class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center">
      <div class="text-5xl mb-4">📭</div>
      <h3 class="text-lg font-bold text-ango-navy mb-2">Sem subscrição activa</h3>
      <p class="text-gray-500 text-sm mb-6">Escolha um plano para começar a usar a API.</p>
      <router-link to="/planos" class="btn-primary text-sm">Ver Planos</router-link>
    </div>

    <div v-if="subStore.list.length" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
      <h3 class="font-semibold text-ango-navy mb-4">Histórico de Subscrições</h3>
      <div class="space-y-3">
        <div v-for="sub in subStore.list" :key="sub.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg text-sm">
          <div>
            <span class="font-medium text-ango-navy">{{ sub.plan?.name }}</span>
            <span class="text-gray-400 ml-2">{{ sub.starts_at }} — {{ sub.ends_at }}</span>
          </div>
          <span class="px-2 py-0.5 rounded-full text-xs font-medium" :class="sub.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'">
            {{ sub.status }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useSubscriptionsStore } from '../../stores/subscriptions'

const subStore = useSubscriptionsStore()

onMounted(() => {
  subStore.fetchCurrent()
  subStore.fetchList()
})
</script>
