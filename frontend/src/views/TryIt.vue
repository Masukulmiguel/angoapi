<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 pt-24 pb-20">
    <!-- Hero -->
    <div class="relative overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-br from-ango-navy via-ango-dark to-ango-navy"></div>
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-ango-red rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-72 h-72 bg-ango-orange rounded-full blur-3xl"></div>
      </div>
      <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 text-sm font-medium text-white/90 mb-6">
          <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
          Sandbox Gratuito — Sem autenticação
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
          Teste as APIs <span class="text-ango-red">em tempo real</span>
        </h1>
        <p class="text-lg text-white/60 max-w-2xl mx-auto">
          Explore cada endpoint antes de integrar. Preencha os campos, clique em testar e veja a resposta instantaneamente.
        </p>
      </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-10">

      <!-- Tabs -->
      <div class="flex gap-2 overflow-x-auto pb-4 mb-8 scrollbar-hide">
        <button v-for="(tab, i) in tabs" :key="i"
          @click="activeTab = i"
          class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-200"
          :class="activeTab === i
            ? 'bg-ango-navy dark:bg-ango-red text-white shadow-lg'
            : 'bg-white dark:bg-gray-900 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-800 hover:border-ango-navy/30 dark:hover:border-ango-red/30'">
          <component :is="tab.icon" class="w-4 h-4" />
          {{ tab.name }}
        </button>
      </div>

      <!-- Active API Card -->
      <transition name="fade" mode="out-in">
        <div :key="activeTab" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-xl overflow-hidden">

          <!-- Card Header -->
          <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-2xl flex items-center justify-center bg-ango-red/10">
                <component :is="activeApi.icon" class="w-6 h-6 text-ango-red" />
              </div>
              <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ activeApi.name }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ activeApi.desc }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-ango-navy/10 dark:bg-ango-navy/30 text-ango-navy dark:text-blue-300">
                {{ activeApi.method }}
              </span>
              <span class="px-3 py-1 rounded-lg text-xs font-medium bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800">
                Sandbox
              </span>
            </div>
          </div>

          <!-- Form -->
          <div class="p-8">
            <div class="grid sm:grid-cols-2 gap-5 mb-6">
              <div v-for="field in activeApi.fields" :key="field.name" :class="field.full ? 'sm:col-span-2' : ''">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                  {{ field.label }} <span v-if="field.required" class="text-ango-red">*</span>
                </label>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <component :is="getFieldIcon(field)" class="w-4 h-4 text-gray-400 dark:text-gray-500" />
                  </div>
                  <input
                    v-model="activeApi.state[field.name]"
                    :type="field.type || 'text'"
                    :placeholder="field.placeholder"
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-white text-sm focus:border-ango-red focus:ring-2 focus:ring-ango-red/20 outline-none transition-all placeholder:text-gray-400 dark:placeholder:text-gray-500"
                  />
                </div>
              </div>
            </div>

            <button @click="runTest(activeApi)" :disabled="activeApi.loading || !hasRequiredFields"
              class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-xl text-sm font-bold text-white transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed bg-ango-red hover:bg-red-600 hover:shadow-lg hover:shadow-ango-red/25 active:scale-[0.98]">
              <Loader2 v-if="activeApi.loading" class="w-4 h-4 animate-spin" />
              <Play v-else class="w-4 h-4" />
              {{ activeApi.loading ? 'A testar...' : 'Executar Teste' }}
            </button>
          </div>

          <!-- Result -->
          <div v-if="activeApi.result" class="border-t border-gray-100 dark:border-gray-800">
            <div class="px-8 py-4 flex items-center justify-between"
              :class="activeApi.result.success ? 'bg-green-50/80 dark:bg-green-900/10' : 'bg-red-50/80 dark:bg-red-900/10'">
              <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                  :class="activeApi.result.success ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30'">
                  <Check v-if="activeApi.result.success" class="w-4 h-4 text-green-600 dark:text-green-400" />
                  <X v-else class="w-4 h-4 text-red-600 dark:text-red-400" />
                </div>
                <span class="text-sm font-semibold"
                  :class="activeApi.result.success ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'">
                  {{ activeApi.result.status }}
                </span>
              </div>
              <span class="text-xs text-gray-400 dark:text-gray-500">{{ activeApi.result.time }}</span>
            </div>
            <div class="bg-gray-950 p-6">
              <pre class="text-sm font-mono text-green-400 leading-relaxed whitespace-pre-wrap overflow-x-auto">{{ formatJson(activeApi.result.data) }}</pre>
            </div>
          </div>
        </div>
      </transition>

      <!-- CTA -->
      <div class="mt-16 relative overflow-hidden rounded-2xl bg-gradient-to-br from-ango-navy to-ango-dark p-10 text-center">
        <div class="absolute inset-0 opacity-20">
          <div class="absolute top-0 right-1/4 w-64 h-64 bg-ango-red rounded-full blur-3xl"></div>
        </div>
        <div class="relative">
          <h2 class="text-2xl font-extrabold text-white mb-3">Pronto para integrar?</h2>
          <p class="text-white/60 mb-8 max-w-lg mx-auto">Crie uma conta gratuita e comece a usar as APIs na sua aplicação hoje mesmo.</p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <router-link to="/register" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl bg-ango-red text-white font-bold hover:bg-red-600 hover:shadow-lg hover:shadow-ango-red/25 transition-all duration-200">
              <Rocket class="w-4 h-4" />
              Criar Conta Gratis
            </router-link>
            <router-link to="/planos" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 rounded-xl border-2 border-white/20 text-white font-bold hover:bg-white/10 transition-all duration-200">
              <BarChart3 class="w-4 h-4" />
              Ver Planos
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, markRaw } from 'vue'
import {
  CreditCard, Hash, Phone, ShieldCheck, MessageSquare, Mail, QrCode,
  Play, Loader2, Check, X, Rocket, BarChart3, FileText, KeyRound,
  User, AtSign, HashIcon
} from 'lucide-vue-next'

const API_URL = import.meta.env.VITE_API_URL || '/api'

const createApi = (config) => reactive({
  ...config,
  state: { ...config.initialState },
  loading: false,
  result: null,
})

import { reactive } from 'vue'

const tabs = [
  { name: 'BI', icon: markRaw(CreditCard) },
  { name: 'NIF', icon: markRaw(Hash) },
  { name: 'Telefone', icon: markRaw(Phone) },
  { name: 'OTP', icon: markRaw(ShieldCheck) },
  { name: 'SMS', icon: markRaw(MessageSquare) },
  { name: 'Email', icon: markRaw(Mail) },
  { name: 'QR Code', icon: markRaw(QrCode) },
]

const apis = reactive([
  createApi({
    name: 'Validar BI',
    desc: 'Bilhete de Identidade Angolano',
    method: 'POST',
    icon: markRaw(CreditCard),
    endpoint: '/v1/sandbox/validate/bi',
    fields: [
      { name: 'bi_number', label: 'Número do BI', placeholder: '001234567LA042', required: true, icon: 'id' }
    ],
    initialState: { bi_number: '' },
  }),
  createApi({
    name: 'Validar NIF',
    desc: 'Número de Identificação Fiscal',
    method: 'POST',
    icon: markRaw(Hash),
    endpoint: '/v1/sandbox/validate/nif',
    fields: [
      { name: 'nif', label: 'Número do NIF', placeholder: '5417682930', required: true, icon: 'hash' }
    ],
    initialState: { nif: '' },
  }),
  createApi({
    name: 'Validar Telefone',
    desc: 'Número Angolano (+244)',
    method: 'POST',
    icon: markRaw(Phone),
    endpoint: '/v1/sandbox/validate/phone',
    fields: [
      { name: 'phone', label: 'Número de Telefone', placeholder: '912345678', required: true, icon: 'phone' }
    ],
    initialState: { phone: '' },
  }),
  createApi({
    name: 'Gerar OTP',
    desc: 'Código de Verificação',
    method: 'POST',
    icon: markRaw(ShieldCheck),
    endpoint: '/v1/sandbox/otp/generate',
    fields: [
      { name: 'phone', label: 'Telefone', placeholder: '912345678', required: true, icon: 'phone' },
      { name: 'length', label: 'Tamanho do Código', placeholder: '6', type: 'number', icon: 'hash' },
    ],
    initialState: { phone: '', length: '' },
  }),
  createApi({
    name: 'Enviar SMS',
    desc: 'Mensagem de Texto',
    method: 'POST',
    icon: markRaw(MessageSquare),
    endpoint: '/v1/sandbox/sms/send',
    fields: [
      { name: 'to', label: 'Número Destino', placeholder: '912345678', required: true, icon: 'phone' },
      { name: 'message', label: 'Mensagem', placeholder: 'Código: 4829', required: true, full: true, icon: 'message' },
    ],
    initialState: { to: '', message: '' },
  }),
  createApi({
    name: 'Enviar Email',
    desc: 'Email Transacional',
    method: 'POST',
    icon: markRaw(Mail),
    endpoint: '/v1/sandbox/email/send',
    fields: [
      { name: 'to', label: 'Email Destino', placeholder: 'cliente@exemplo.com', type: 'email', required: true, icon: 'email' },
      { name: 'subject', label: 'Assunto', placeholder: 'Bem-vindo à AngoAPI', required: true, icon: 'text' },
      { name: 'body', label: 'Corpo do Email', placeholder: 'Obrigado por se registar!', required: true, full: true, icon: 'text' },
    ],
    initialState: { to: '', subject: '', body: '' },
  }),
  createApi({
    name: 'Gerar QR Code',
    desc: 'Código QR Personalizado',
    method: 'POST',
    icon: markRaw(QrCode),
    endpoint: '/v1/sandbox/qrcode/generate',
    fields: [
      { name: 'data', label: 'Dados a Codificar', placeholder: 'https://angoapi.co.ao', required: true, icon: 'text' },
      { name: 'size', label: 'Tamanho (px)', placeholder: '300', type: 'number', icon: 'hash' },
    ],
    initialState: { data: '', size: '' },
  }),
])

const activeTab = ref(0)
const activeApi = computed(() => apis[activeTab.value])

const hasRequiredFields = computed(() => {
  return activeApi.value.fields
    .filter(f => f.required)
    .every(f => activeApi.value.state[f.name] !== '' && activeApi.value.state[f.name] !== null && activeApi.value.state[f.name] !== undefined)
})

const getFieldIcon = (field) => {
  const map = {
    id: CreditCard,
    hash: HashIcon,
    phone: Phone,
    email: AtSign,
    message: MessageSquare,
    text: FileText,
  }
  return markRaw(map[field.icon] || FileText)
}

const formatJson = (str) => {
  try {
    return JSON.stringify(JSON.parse(str), null, 2)
  } catch {
    return str
  }
}

const runTest = async (api) => {
  api.loading = true
  api.result = null
  const start = performance.now()

  try {
    const body = {}
    for (const [key, val] of Object.entries(api.state)) {
      if (val !== '' && val !== null && val !== undefined) {
        body[key] = api.fields.find(f => f.name === key)?.type === 'number' ? Number(val) : val
      }
    }

    const response = await fetch(API_URL + api.endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(body),
    })

    const elapsed = Math.round(performance.now() - start)
    const data = await response.json()

    api.result = {
      success: response.ok,
      status: response.ok ? `${response.status} OK` : `${response.status} Erro`,
      data: JSON.stringify(data),
      time: `${elapsed}ms`,
    }
  } catch (err) {
    const elapsed = Math.round(performance.now() - start)
    api.result = {
      success: false,
      status: 'Falha na Conexão',
      data: JSON.stringify({ error: err.message }),
      time: `${elapsed}ms`,
    }
  } finally {
    api.loading = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
}
.fade-enter-from {
  opacity: 0;
  transform: translateY(8px);
}
.fade-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
