<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-950 flex flex-col lg:flex-row">
    <!-- Mobile endpoint selector -->
    <div class="lg:hidden sticky top-16 z-20 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 px-4 py-3">
      <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-1">
        <button v-for="ep in allEndpoints" :key="ep.id" @click="selectEndpoint(ep)"
          class="flex-shrink-0 flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-medium transition-colors border"
          :class="activeEndpoint?.id === ep.id
            ? 'bg-blue-50 dark:bg-blue-900/30 border-blue-300 dark:border-blue-700 text-blue-700 dark:text-blue-300'
            : 'bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400'">
          <span :class="['px-1.5 py-0.5 rounded text-[10px] font-bold uppercase', ep.method === 'POST' ? 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300' : 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300']">{{ ep.method }}</span>
          <span class="truncate max-w-[120px]">{{ ep.title }}</span>
        </button>
      </div>
    </div>

    <!-- Desktop sidebar -->
    <aside class="w-64 bg-white dark:bg-gray-950 border-r border-gray-200 dark:border-gray-700 sticky top-0 h-screen overflow-y-auto hidden lg:block flex-shrink-0">
      <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">API Docs</h2>
        <div class="relative">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input v-model="search" type="text" placeholder="Pesquisar endpoints..." class="w-full pl-9 pr-3 py-2 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent placeholder:text-gray-400 dark:placeholder:text-gray-500" />
        </div>
      </div>
      <nav class="p-2">
        <div v-for="group in filteredGroups" :key="group.name" class="mb-4">
          <h3 class="px-3 py-1 text-xs font-semibold text-gray-500 dark:text-gray-500 uppercase tracking-wider">{{ group.name }}</h3>
          <div class="mt-1 space-y-0.5">
            <button v-for="endpoint in group.endpoints" :key="endpoint.id" @click="selectEndpoint(endpoint)" :class="['w-full text-left px-3 py-2 rounded-lg text-sm flex items-center gap-2 transition-colors', activeEndpoint?.id === endpoint.id ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800']">
              <FileText class="w-4 h-4 flex-shrink-0" />
              <span class="truncate">{{ endpoint.title }}</span>
            </button>
          </div>
        </div>
        <div v-if="filteredGroups.length === 0" class="flex flex-col items-center gap-2 py-8 text-gray-400 dark:text-gray-500 text-sm">
          <FileText class="w-5 h-5" />
          <span>Nenhum resultado</span>
        </div>
      </nav>
    </aside>
    <main class="flex-1 min-w-0 p-6 lg:p-8">
      <div v-if="!activeEndpoint" class="flex flex-col items-center justify-center min-h-[60vh] text-center">
        <BookOpen class="w-12 h-12 mb-4 text-gray-400 dark:text-gray-500" />
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Selecione um endpoint</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Escolha um endpoint na barra lateral para ver a documentacao.</p>
      </div>
      <div v-else>
        <div class="flex items-center gap-1 mb-6 border-b border-gray-200 dark:border-gray-700">
          <button @click="activeTab = 'docs'" :class="['px-4 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px', activeTab === 'docs' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600']">
            <div class="flex items-center gap-2"><FileText class="w-4 h-4" />Documentacao</div>
          </button>
          <button @click="activeTab = 'test'" :class="['px-4 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px', activeTab === 'test' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600']">
            <div class="flex items-center gap-2"><Play class="w-4 h-4" />Testar API</div>
          </button>
        </div>
        <div v-if="activeTab === 'docs'" class="max-w-4xl">
          <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="flex items-center gap-3 mb-4">
              <span :class="['px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wide', methodColor(activeEndpoint.method)]">{{ activeEndpoint.method }}</span>
              <code class="text-sm font-mono text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-lg">{{ activeEndpoint.path }}</code>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ activeEndpoint.title }}</h1>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ activeEndpoint.description }}</p>
            <div v-if="activeEndpoint.auth" class="mt-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg flex items-center gap-2 text-amber-800 dark:text-amber-300 text-sm">
              <Shield class="w-4 h-4 flex-shrink-0" />
              <span>Este endpoint requer autenticacao. Inclua o header <code class="font-mono bg-amber-100 dark:bg-amber-900/40 px-1.5 py-0.5 rounded">Authorization: Bearer &lt;token&gt;</code></span>
            </div>
            <div v-else class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg flex items-center gap-2 text-green-800 dark:text-green-300 text-sm">
              <Shield class="w-4 h-4 flex-shrink-0" />
              <span>Este endpoint nao requer autenticacao.</span>
            </div>
          </div>
          <div v-if="activeEndpoint.params && activeEndpoint.params.length" class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2"><List class="w-5 h-5" />Parametros</h2>
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Nome</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Tipo</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Obrigatorio</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Descricao</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="param in activeEndpoint.params" :key="param.name" class="border-b border-gray-100 dark:border-gray-800 last:border-0">
                    <td class="py-3 px-4 font-mono text-blue-600 dark:text-blue-400">{{ param.name }}</td>
                    <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ param.type }}</td>
                    <td class="py-3 px-4">
                      <span v-if="param.required" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">Sim</span>
                      <span v-else class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">Nao</span>
                    </td>
                    <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ param.description }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2"><Code class="w-5 h-5" />Exemplos</h2>
            <div class="flex items-center gap-1 mb-4 border-b border-gray-200 dark:border-gray-700">
              <button v-for="tab in ['curl', 'js', 'python']" :key="tab" @click="exampleTab = tab" :class="['px-3 py-2 text-sm font-medium border-b-2 transition-colors -mb-px', exampleTab === tab ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200']">{{ tab.toUpperCase() }}</button>
            </div>
            <div class="bg-gray-900 rounded-xl overflow-hidden">
              <div class="flex items-center justify-between px-4 py-2.5 border-b border-white/10">
                <span class="text-xs font-medium text-gray-400">{{ exampleTab === 'curl' ? 'cURL' : exampleTab === 'js' ? 'JavaScript' : 'Python' }}</span>
                <button @click="copyExample" class="flex items-center gap-1.5 px-2.5 py-1 bg-white/5 border border-white/10 rounded-md text-xs font-medium text-gray-400 cursor-pointer hover:bg-white/10 hover:text-gray-200 transition-colors">
                  <Loader2 v-if="copied === 'copying'" :size="14" class="animate-spin" />
                  <Check v-else-if="copied === 'done'" :size="14" />
                  <Copy v-else :size="14" />
                  <span>{{ copied === 'copying' ? 'Copiando...' : copied === 'done' ? 'Copiado!' : 'Copiar' }}</span>
                </button>
              </div>
              <pre class="p-4 m-0 overflow-x-auto"><code class="font-mono text-sm leading-relaxed text-gray-200 whitespace-pre">{{ getExample }}</code></pre>
            </div>
          </div>
          <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2"><Terminal class="w-5 h-5" />Resposta</h2>
            <div class="bg-gray-900 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
              <pre class="p-4 m-0 overflow-x-auto"><code class="font-mono text-sm leading-relaxed text-gray-200 whitespace-pre">{{ JSON.stringify(activeEndpoint.testResponse, null, 2) }}</code></pre>
            </div>
          </div>
        </div>
        <div v-if="activeTab === 'test'" class="max-w-4xl">
          <div class="flex items-center gap-4 p-5 bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl mb-7">
            <div class="w-12 h-12 rounded-xl bg-amber-500/15 flex items-center justify-center text-amber-400 flex-shrink-0"><FlaskConical :size="24" /></div>
            <div class="flex-1">
              <h3 class="text-base font-semibold text-gray-100 mb-0.5">Modo Sandbox</h3>
              <p class="text-sm text-gray-400 m-0">Teste os endpoints em ambiente de demonstracao.</p>
            </div>
            <span class="px-3 py-1 bg-amber-500/20 text-amber-400 rounded-full text-xs font-bold tracking-wider flex-shrink-0">SANDBOX</span>
          </div>
          <div class="flex flex-col gap-5 mb-7">
            <div v-for="param in activeEndpoint.params" :key="param.name" class="flex flex-col gap-1.5">
              <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ param.name }}<span v-if="param.required" class="text-red-500">*</span></label>
              <input :id="param.name" v-model="testParams[param.name]" :type="param.type === 'integer' ? 'number' : 'text'" :placeholder="getParamPlaceholder(param)" class="px-3 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm font-mono text-gray-900 dark:text-white outline-none transition-all focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 placeholder:text-gray-400 dark:placeholder:text-gray-500 placeholder:font-sans" />
            </div>
            <button @click="executeTest" :disabled="testing" class="inline-flex items-center justify-center gap-2 px-7 py-3 bg-red-600 rounded-lg text-sm font-semibold text-white cursor-pointer transition-colors hover:bg-red-700 disabled:opacity-60 disabled:cursor-not-allowed self-start">
              <Loader2 v-if="testing" :size="16" class="animate-spin" />
              <Play v-else :size="16" />
              <span>{{ testing ? 'Executando...' : 'Executar' }}</span>
            </button>
          </div>
          <div v-if="testResult" class="flex flex-col gap-4">
            <div :class="['flex items-center gap-2.5 px-4 py-3.5 rounded-xl text-sm font-semibold', testResult.success ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800']">
              <Check v-if="testResult.success" :size="18" />
              <X v-else :size="18" />
              <span>{{ testResult.status }}</span>
            </div>
            <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <div class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-600 dark:text-gray-400">Resposta</div>
              <div class="bg-gray-900">
                <pre class="p-4 m-0 overflow-x-auto"><code class="font-mono text-sm leading-relaxed text-gray-200 whitespace-pre">{{ JSON.stringify(testResult.data, null, 2) }}</code></pre>
              </div>
            </div>
            <div v-if="testResult.qr" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
              <div class="px-4 py-2.5 bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-600 dark:text-gray-400">QR Code</div>
              <img :src="testResult.qr" alt="QR Code" class="block max-w-[300px] p-4" />
            </div>
          </div>
          <div v-if="!testResult && !testing" class="flex flex-col items-center gap-3 py-12 text-gray-400 dark:text-gray-500 text-center">
            <Terminal :size="40" />
            <p class="text-sm m-0">Preencha os parametros e clique em Executar para testar.</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script setup>
import { ref, computed } from 'vue'
import {
  Search,
  FileText,
  Play,
  Shield,
  List,
  Code,
  Copy,
  ArrowDown,
  FlaskConical,
  PencilLine,
  Loader2,
  Check,
  X,
  Terminal,
  BookOpen,
} from 'lucide-vue-next'

const API_URL = import.meta.env.VITE_API_URL || '/api'

const search = ref('')
const exampleTab = ref('curl')
const copied = ref(null)
const activeEndpoint = ref(null)
const activeTab = ref('docs')
const testParams = ref({})
const testing = ref(false)
const testResult = ref(null)

const methodColor = (method) => {
  const colors = {
    GET: 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300',
    POST: 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300',
    PUT: 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300',
    DELETE: 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300',
  }
  return colors[method] || 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'
}

const groups = [
  {
    name: 'Validacao',
    endpoints: [
      {
        id: 'validate-bi',
        method: 'POST',
        path: '/api/v1/sandbox/validate/bi',
        title: 'Validar Bilhete de Identidade',
        description: 'Valida um numero de bilhete de identidade angolano e retorna os dados do titular.',
        auth: false,
        params: [
          { name: 'bi_number', type: 'string', required: true, description: 'Numero do bilhete de identidade (ex: 001234567LA042)' }
        ],
        testExample: { bi_number: '001234567LA042' },
        testResponse: {
          valid: true,
          data: { bi_number: '001234567LA042', full_name: 'Joao Manuel Silva', date_of_birth: '1990-05-15', address: 'LUANDA, MAIANGA, RUA 5', nationality: 'Angolana' },
          source: 'Edgar Singui API',
          environment: 'sandbox'
        }
      },
      {
        id: 'validate-nif',
        method: 'POST',
        path: '/api/v1/sandbox/validate/nif',
        title: 'Validar NIF',
        description: 'Valida um numero de identificacao fiscal (NIF) e retorna os dados da empresa.',
        auth: false,
        params: [
          { name: 'nif', type: 'string', required: true, description: 'Numero de identificacao fiscal (ex: 5417682930)' }
        ],
          testExample: { nif: '5417682930' },
        testResponse: {
          valid: true,
          data: { nif: '5417682930', company_name: 'EMPRESA EXEMPLO LDA', status: 'Ativo', address: 'LUANDA, MAIANGA' },
          source: 'Edgar Singui API',
          environment: 'sandbox'
        }
      },
      {
        id: 'phone-validate',
        method: 'POST',
        path: '/api/v1/sandbox/validate/phone',
        title: 'Validar Telefone',
        description: 'Valida um numero de telefone angolano e retorna informacoes sobre a operadora.',
        auth: false,
        params: [
          { name: 'phone', type: 'string', required: true, description: 'Numero de telefone (ex: 912345678)' }
        ],
        testExample: { phone: '912345678' },
        testResponse: {
          valid: true,
          data: { phone: '912345678', carrier: 'Unitel', type: 'Movel', country: 'Angola' },
          environment: 'sandbox'
        }
      }
    ]
  },
  {
    name: 'SMS',
    endpoints: [
      {
        id: 'sms-send',
        method: 'POST',
        path: '/api/v1/sandbox/sms/send',
        title: 'Enviar SMS',
        description: 'Envia uma mensagem SMS para um numero de telefone angolano.',
        auth: false,
        params: [
          { name: 'to', type: 'string', required: true, description: 'Numero de destino (ex: 912345678)' },
          { name: 'message', type: 'string', required: true, description: 'Conteudo da mensagem' }
        ],
        testExample: { to: '912345678', message: 'Codigo: 4829' },
        testResponse: {
          success: true,
          message_id: 'sms_abc123',
          to: '912345678',
          status: 'queued',
          environment: 'sandbox'
        }
      }
    ]
  },
  {
    name: 'OTP',
    endpoints: [
      {
        id: 'otp-generate',
        method: 'POST',
        path: '/api/v1/sandbox/otp/generate',
        title: 'Gerar OTP',
        description: 'Gera um codigo de verificacao unico (OTP) e envia por SMS.',
        auth: false,
        params: [
          { name: 'phone', type: 'string', required: true, description: 'Numero de telefone' },
          { name: 'length', type: 'integer', required: false, description: 'Tamanho do codigo (4-8, padrao: 6)' }
        ],
        testExample: { phone: '912345678', length: 6 },
        testResponse: {
          success: true,
          token: 'otp_xyz789',
          phone: '912345678',
          expires_in: 300,
          environment: 'sandbox'
        }
      },
      {
        id: 'otp-verify',
        method: 'POST',
        path: '/api/v1/sandbox/otp/verify',
        title: 'Verificar OTP',
        description: 'Verifica se o codigo OTP fornecido esta correto.',
        auth: false,
        params: [
          { name: 'phone', type: 'string', required: true, description: 'Numero de telefone' },
          { name: 'token', type: 'string', required: true, description: 'Token retornado ao gerar o OTP' },
          { name: 'code', type: 'string', required: true, description: 'Codigo recebido por SMS' }
        ],
        testExample: { phone: '912345678', token: '', code: '' },
        testResponse: {
          valid: true,
          phone: '912345678',
          environment: 'sandbox'
        }
      }
    ]
  },
  {
    name: 'Email',
    endpoints: [
      {
        id: 'email-send',
        method: 'POST',
        path: '/api/v1/sandbox/email/send',
        title: 'Enviar Email',
        description: 'Envia um email HTML para o endereco especificado.',
        auth: false,
        params: [
          { name: 'to', type: 'email', required: true, description: 'Endereco de destino' },
          { name: 'subject', type: 'string', required: true, description: 'Assunto do email' },
          { name: 'body', type: 'string', required: true, description: 'Corpo do email em HTML' }
        ],
        testExample: { to: 'cliente@exemplo.com', subject: 'Bem-vindo', body: 'Obrigado!' },
        testResponse: {
          success: true,
          message_id: 'email_def456',
          to: 'cliente@exemplo.com',
          status: 'queued',
          environment: 'sandbox'
        }
      }
    ]
  },
  {
    name: 'QR Code',
    endpoints: [
      {
        id: 'qr-generate',
        method: 'POST',
        path: '/api/v1/sandbox/qrcode/generate',
        title: 'Gerar QR Code',
        description: 'Gera um QR Code a partir dos dados fornecidos.',
        auth: false,
        params: [
          { name: 'data', type: 'string', required: true, description: 'Dados a codificar no QR Code' },
          { name: 'size', type: 'integer', required: false, description: 'Tamanho em pixels (padrao: 300)' }
        ],
        testExample: { data: 'https://angoapi.co.ao', size: 300 },
        testResponse: {
          success: true,
          qr_url: 'https://api.angoapi.co.ao/qr/abc123.png',
          format: 'png',
          size: 300,
          environment: 'sandbox'
        }
      }
    ]
  }
]

const filteredGroups = computed(() => {
  if (!search.value) return groups
  const query = search.value.toLowerCase()
  return groups
    .map(group => ({
      ...group,
      endpoints: group.endpoints.filter(
        e => e.title.toLowerCase().includes(query) ||
             e.path.toLowerCase().includes(query) ||
             e.description.toLowerCase().includes(query)
      )
    }))
    .filter(group => group.endpoints.length > 0)
})

const allEndpoints = computed(() => {
  return groups.flatMap(g => g.endpoints)
})

const selectEndpoint = (endpoint) => {
  activeEndpoint.value = endpoint
  activeTab.value = 'docs'
  testParams.value = { ...endpoint.testExample }
  testResult.value = null
}

const getParamPlaceholder = (param) => {
  const placeholders = {
    bi_number: '001234567LA042',
    nif: '5417682930',
    phone: '912345678',
    to: '912345678',
    message: 'Codigo: 4829',
    length: '6',
    token: 'otp_xyz789',
    code: '123456',
    subject: 'Bem-vindo',
    body: 'Obrigado!',
    data: 'https://angoapi.co.ao',
    size: '300'
  }
  return placeholders[param.name] || ''
}

const getExample = computed(() => {
  if (!activeEndpoint.value) return ''
  const ep = activeEndpoint.value
  const params = ep.params
  const baseUrl = API_URL

  if (exampleTab.value === 'curl') {
    let cmd = 'curl -X ' + ep.method + ' "' + baseUrl + ep.path + '"'
    cmd += ' \\\n  -H "Content-Type: application/json"'
    if (ep.auth) cmd += ' \\\n  -H "Authorization: Bearer SEU_TOKEN"'
    if (ep.method === 'POST' && params.length) {
      const body = {}
      params.forEach(p => { body[p.name] = ep.testExample[p.name] || '' })
      cmd += " \\\n  -d '" + JSON.stringify(body) + "'"
    }
    return cmd
  }

  if (exampleTab.value === 'js') {
    const bodyObj = {}
    params.forEach(p => { bodyObj[p.name] = ep.testExample[p.name] || '' })
    const headers = { 'Content-Type': 'application/json' }
    if (ep.auth) headers['Authorization'] = 'Bearer SEU_TOKEN'
    return 'const response = await fetch(\'' + baseUrl + ep.path + '\', {\n' +
      '  method: \'' + ep.method + '\',\n' +
      '  headers: ' + JSON.stringify(headers, null, 2) + ',\n' +
      '  body: ' + (params.length ? 'JSON.stringify(' + JSON.stringify(bodyObj, null, 2) + ')' : 'null') + '\n' +
      '});\n\nconst data = await response.json();\nconsole.log(data);'
  }

  if (exampleTab.value === 'python') {
    const pyBody = {}
    params.forEach(p => { pyBody[p.name] = ep.testExample[p.name] || '' })
    let py = 'import requests\n\n'
    py += 'url = "' + baseUrl + ep.path + '"\n'
    py += 'headers = {\n    "Content-Type": "application/json",\n'
    if (ep.auth) py += '    "Authorization": "Bearer SEU_TOKEN",\n'
    py += '}\n'
    if (params.length) {
      py += 'payload = ' + JSON.stringify(pyBody, null, 4).replace(/null/g, 'None').replace(/true/g, 'True').replace(/false/g, 'False') + '\n\n'
      py += 'response = requests.' + ep.method.toLowerCase() + '(url, json=payload, headers=headers)\n'
    } else {
      py += '\nresponse = requests.' + ep.method.toLowerCase() + '(url, headers=headers)\n'
    }
    py += 'print(response.json())'
    return py
  }

  return ''
})

const copyExample = async () => {
  copied.value = 'copying'
  try {
    await navigator.clipboard.writeText(getExample.value)
    copied.value = 'done'
    setTimeout(() => { copied.value = null }, 2000)
  } catch {
    copied.value = null
  }
}

const sandboxRoutes = {
  'validate-bi': '/v1/sandbox/validate/bi',
  'validate-nif': '/v1/sandbox/validate/nif',
  'phone-validate': '/v1/sandbox/validate/phone',
  'otp-generate': '/v1/sandbox/otp/generate',
  'otp-verify': '/v1/sandbox/otp/verify',
  'sms-send': '/v1/sandbox/sms/send',
  'email-send': '/v1/sandbox/email/send',
  'qr-generate': '/v1/sandbox/qrcode/generate'
}

const executeTest = async () => {
  if (!activeEndpoint.value) return
  testing.value = true
  testResult.value = null

  try {
    const route = sandboxRoutes[activeEndpoint.value.id]
    const response = await fetch(API_URL + route, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer sandbox_test_token'
      },
      body: JSON.stringify(testParams.value)
    })

    const data = await response.json()

    testResult.value = {
      success: response.ok,
      status: response.status + ' ' + response.statusText,
      data: data,
      qr: data.qr_url || data.qr_base64 || null
    }
  } catch (err) {
    testResult.value = {
      success: false,
      status: 'Erro de conexao',
      data: { error: err.message }
    }
  } finally {
    testing.value = false
  }
}

selectEndpoint(groups[0].endpoints[0])
</script>
