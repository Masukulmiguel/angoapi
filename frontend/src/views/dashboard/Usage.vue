<template>
  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-bold text-ango-navy">Consumo</h1>
      <p class="text-gray-500 text-sm mt-1">Acompanhe o uso da API</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Requisições hoje</p>
        <p class="text-3xl font-bold text-ango-navy">0</p>
      </div>
      <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Este mês</p>
        <p class="text-3xl font-bold text-ango-navy">0</p>
      </div>
      <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Limite mensal</p>
        <p class="text-3xl font-bold text-ango-navy">—</p>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
      <h3 class="font-semibold text-ango-navy mb-4">Uso por Endpoint</h3>
      <div class="space-y-3">
        <div v-for="(ep, i) in endpoints" :key="i" class="flex items-center gap-4">
          <div class="flex-1">
            <div class="flex justify-between text-sm mb-1">
              <span class="text-gray-600 font-mono text-xs">{{ ep.name }}</span>
              <span class="text-gray-400 text-xs">{{ ep.count }} chamadas</span>
            </div>
            <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
              <div class="h-full rounded-full bg-ango-red transition-all" :style="{ width: ep.pct + '%' }" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
      <h3 class="font-semibold text-ango-navy mb-4">Últimas Requisições</h3>
      <p class="text-sm text-gray-400 text-center py-8">Sem dados de utilização ainda.</p>
    </div>
  </div>
</template>

<script setup>
const endpoints = [
  { name: '/validate/bi', count: 0, pct: 0 },
  { name: '/validate/nif', count: 0, pct: 0 },
  { name: '/sms/send', count: 0, pct: 0 },
  { name: '/otp/generate', count: 0, pct: 0 },
  { name: '/email/send', count: 0, pct: 0 },
]
</script>
