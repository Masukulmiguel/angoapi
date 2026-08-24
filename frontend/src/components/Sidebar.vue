<template>
  <aside class="fixed left-0 top-0 bottom-0 bg-ango-navy dark:bg-gray-900 text-white z-40 transition-all duration-300 flex flex-col"
         :class="collapsed ? 'w-16' : 'w-64'">
    <div class="h-16 flex items-center px-4 border-b border-white/10">
      <router-link to="/" class="flex items-center gap-2 overflow-hidden">
        <img src="/logo-fundo-escuro.png" alt="AngoAPI" class="w-8 h-8 flex-shrink-0" />
        <span v-if="!collapsed" class="text-lg font-bold whitespace-nowrap">AngoAPI</span>
      </router-link>
    </div>

    <nav class="flex-1 py-4 overflow-y-auto">
      <router-link v-for="item in menuItems" :key="item.to" :to="item.to"
        class="flex items-center gap-3 px-4 py-3 text-sm font-medium transition-colors rounded-lg mx-2 mb-1"
        :class="$route.path === item.to ? 'bg-white/15 text-white' : 'text-gray-300 hover:bg-white/10 hover:text-white'">
        <component :is="item.icon" class="w-5 h-5 flex-shrink-0" />
        <span v-if="!collapsed" class="whitespace-nowrap">{{ item.label }}</span>
      </router-link>
    </nav>

    <div class="p-3 border-t border-white/10">
      <button @click="$emit('toggle')"
        class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-white/10 hover:text-white transition-colors">
        <svg class="w-5 h-5 transition-transform" :class="collapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
        </svg>
        <span v-if="!collapsed">Recolher</span>
      </button>
    </div>
  </aside>
</template>

<script setup>
import { h } from 'vue'

defineProps({ collapsed: Boolean })
defineEmits(['toggle'])

const Icon = (d) => ({
  render() { return h('svg', { class: 'w-5 h-5', fill: 'none', stroke: 'currentColor', viewBox: '0 0 24 24', innerHTML: d }) }
})

const menuItems = [
  { to: '/dashboard', label: 'Visão Geral', icon: Icon('M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6') },
  { to: '/dashboard/account', label: 'Conta', icon: Icon('M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z') },
  { to: '/dashboard/subscription', label: 'Subscrição', icon: Icon('M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z') },
  { to: '/dashboard/api-keys', label: 'API Keys', icon: Icon('M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z') },
  { to: '/dashboard/payments', label: 'Pagamentos', icon: Icon('M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z') },
  { to: '/dashboard/usage', label: 'Consumo', icon: Icon('M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z') },
  { to: '/dashboard/settings', label: 'Configurações', icon: Icon('M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z') },
]

const logoutItem = {
  label: 'Sair',
  icon: Icon('M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1'),
}
</script>
