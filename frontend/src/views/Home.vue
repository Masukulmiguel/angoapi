<template>
  <div>
    <!-- HERO -->
    <section class="relative min-h-screen flex items-center overflow-hidden bg-ango-dark">
      <div class="absolute inset-0 opacity-[0.03]"
           style="background-image: linear-gradient(rgba(255,255,255,.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.1) 1px, transparent 1px); background-size: 60px 60px;" />
      <div class="absolute top-1/4 -left-32 w-96 h-96 bg-ango-red/10 rounded-full blur-3xl parallax-el" />
      <div class="absolute bottom-1/4 -right-32 w-96 h-96 bg-ango-orange/10 rounded-full blur-3xl parallax-el" />
      <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-ango-gold/5 rounded-full blur-3xl parallax-el" />

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
          <div class="hero-content">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-[1.1] mb-6">
              APIs que conectam o seu negócio ao ecossistema digital
              <span class="text-ango-red">angolano</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-400 leading-relaxed mb-10 max-w-xl">
              Integre serviços de validação e dados através de APIs simples, seguras e preparadas para empresas e developers.
            </p>
            <div class="flex flex-wrap gap-4">
              <router-link to="/register" class="btn-primary text-base !px-8 !py-3.5">
                Começar agora
                <svg class="inline-block w-4 h-4 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
              </router-link>
              <router-link to="/try-it"
                class="px-8 py-3.5 rounded-lg border-2 border-green-400/50 text-green-400 font-semibold hover:bg-green-400/10 transition-all">
                Testar Gratis
              </router-link>
            </div>
          </div>

          <div class="hero-code hidden lg:block">
            <div class="bg-[#0a1628] rounded-2xl border border-white/10 overflow-hidden shadow-2xl">
              <div class="flex items-center gap-2 px-4 py-3 border-b border-white/10">
                <span class="w-3 h-3 rounded-full bg-red-500/80" />
                <span class="w-3 h-3 rounded-full bg-yellow-500/80" />
                <span class="w-3 h-3 rounded-full bg-green-500/80" />
                <span class="ml-3 text-xs text-gray-500 font-mono">request.js</span>
              </div>
              <pre class="p-6 text-sm font-mono leading-relaxed overflow-x-auto"><code class="text-gray-300"><span class="text-ango-orange">const</span> <span class="text-blue-300">response</span> = <span class="text-ango-orange">await</span> <span class="text-green-300">fetch</span>(<span class="text-ango-gold">'/api/v1/validate/bi'</span>, {
  <span class="text-gray-500">method:</span> <span class="text-ango-gold">'POST'</span>,
  <span class="text-gray-500">headers:</span> {
    <span class="text-ango-gold">'Authorization'</span>: <span class="text-ango-gold">`Bearer ${<span class="text-blue-300">API_KEY</span>}`</span>,
    <span class="text-ango-gold">'Content-Type'</span>: <span class="text-ango-gold">'application/json'</span>,
  },
  <span class="text-gray-500">body:</span> <span class="text-green-300">JSON.stringify</span>({
    <span class="text-gray-500">bi_number:</span> <span class="text-ango-gold">'001234567LA042'</span>
  })
});

<span class="text-ango-orange">const</span> <span class="text-blue-300">data</span> = <span class="text-ango-orange">await</span> <span class="text-blue-300">response</span>.<span class="text-green-300">json</span>();
<span class="text-gray-500">// { valid: true, name: "João Silva", ... }</span></code></pre>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- COMO FUNCIONA -->
    <section class="py-24 bg-white dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 anim-el">
          <h2 class="section-title">Como Funciona</h2>
          <p class="section-subtitle">Seis passos simples para começar a integrar</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
          <div v-for="(step, i) in howItWorks" :key="i" class="text-center anim-el">
            <div class="relative mb-4">
              <div class="w-16 h-16 mx-auto rounded-2xl bg-ango-navy/10 dark:bg-white/10 flex items-center justify-center"
                   :class="step.gradient">
                <component :is="step.icon" class="w-7 h-7 text-ango-navy dark:text-white" />
              </div>
              <span class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-ango-navy text-white text-xs font-bold flex items-center justify-center">{{ i + 1 }}</span>
            </div>
            <h3 class="text-sm font-semibold text-ango-navy dark:text-white mb-1">{{ step.title }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ step.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- APIs DISPONÍVEIS -->
    <section class="py-24 bg-gray-50 dark:bg-gray-950">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 anim-el">
          <h2 class="section-title">APIs Disponíveis</h2>
          <p class="section-subtitle">Serviços robustos para integrar na sua aplicação</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="(api, i) in apis" :key="i" class="card group cursor-pointer anim-el dark:bg-gray-900 dark:border-gray-800">
            <div class="flex items-start justify-between mb-4">
              <div class="w-12 h-12 rounded-xl flex items-center justify-center" :class="api.bg">
                <component :is="api.icon" class="w-6 h-6 text-ango-navy dark:text-white" />
              </div>
            </div>
            <h3 class="text-lg font-bold text-ango-navy dark:text-white mb-2 group-hover:text-ango-red transition-colors">{{ api.name }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ api.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- PLANOS -->
    <section class="py-24 bg-white dark:bg-gray-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 anim-el">
          <h2 class="section-title">Planos</h2>
          <p class="section-subtitle">Escolha o plano ideal para o seu negócio</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
          <div v-for="(plan, i) in plans" :key="i"
            class="relative rounded-2xl p-8 transition-all duration-300 hover:-translate-y-2 anim-el"
            :class="plan.featured ? 'bg-ango-navy text-white shadow-2xl scale-105 border-2 border-ango-gold' : 'bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-800 shadow-lg hover:shadow-xl'">
            <div v-if="plan.featured" class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-ango-gold text-ango-dark text-xs font-bold">
              MAIS POPULAR
            </div>
            <div class="mb-6">
              <h3 class="text-xl font-bold mb-2" :class="plan.featured ? 'text-white' : 'text-ango-navy dark:text-white'">{{ plan.name }}</h3>
              <p class="text-sm" :class="plan.featured ? 'text-gray-300' : 'text-gray-500 dark:text-gray-400'">{{ plan.desc }}</p>
            </div>
            <div class="mb-8">
              <span class="text-4xl font-extrabold" :class="plan.featured ? 'text-white' : 'text-ango-navy dark:text-white'">{{ plan.price }}</span>
              <span class="text-sm ml-1" :class="plan.featured ? 'text-gray-300' : 'text-gray-500 dark:text-gray-400'">Kz/mês</span>
            </div>
            <ul class="space-y-3 mb-8">
              <li v-for="(f, fi) in plan.features" :key="fi" class="flex items-start gap-3 text-sm">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" :class="plan.featured ? 'text-ango-gold' : 'text-green-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span :class="plan.featured ? 'text-gray-200' : 'text-gray-600 dark:text-gray-400'">{{ f }}</span>
              </li>
            </ul>
            <router-link to="/register" class="block text-center py-3 rounded-lg font-semibold text-sm transition-all"
              :class="plan.featured ? 'bg-ango-gold text-ango-dark hover:bg-yellow-400' : 'bg-ango-navy text-white hover:bg-ango-navy/90'">
              {{ plan.cta }}
            </router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- SEGURANÇA -->
    <section class="py-24 bg-ango-dark dark:bg-gray-950">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 anim-el">
          <h2 class="text-3xl md:text-4xl font-bold text-white">Segurança</h2>
          <p class="text-lg text-gray-400 mt-4 max-w-2xl mx-auto">A sua segurança é a nossa prioridade. Todas as APIs são protegidas por múltiplas camadas.</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-6">
          <div v-for="(feat, i) in securityFeatures" :key="i" class="text-center p-6 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all anim-el">
            <div class="w-14 h-14 mx-auto mb-4 rounded-xl bg-white/10 flex items-center justify-center">
              <component :is="feat.icon" class="w-7 h-7 text-ango-gold" />
            </div>
            <h4 class="text-white font-semibold text-sm mb-2">{{ feat.title }}</h4>
            <p class="text-gray-400 text-xs leading-relaxed">{{ feat.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- DEVELOPERS -->
    <section class="py-24 bg-gray-50 dark:bg-gray-950">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
          <div class="anim-el">
            <h2 class="section-title mb-6">Feito para Developers</h2>
            <p class="text-gray-500 dark:text-gray-400 leading-relaxed mb-8">
              Documentação clara, SDKs oficiais e exemplos práticos para integrar rapidamente. Suporte para cURL, JavaScript, Python e mais.
            </p>
            <div class="space-y-4">
              <div v-for="(f, i) in devFeatures" :key="i" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-800">
                <div class="w-10 h-10 rounded-lg bg-ango-navy/5 dark:bg-white/10 flex items-center justify-center">
                  <component :is="f.icon" class="w-5 h-5 text-ango-navy dark:text-white" />
                </div>
                <div>
                  <h4 class="font-semibold text-sm text-ango-navy dark:text-white">{{ f.title }}</h4>
                  <p class="text-xs text-gray-500 dark:text-gray-400">{{ f.desc }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="anim-el">
            <div class="bg-[#0a1628] rounded-2xl border border-white/10 overflow-hidden shadow-2xl">
              <div class="flex items-center gap-2 px-4 py-3 border-b border-white/10">
                <span class="w-3 h-3 rounded-full bg-red-500/80" />
                <span class="w-3 h-3 rounded-full bg-yellow-500/80" />
                <span class="w-3 h-3 rounded-full bg-green-500/80" />
                <div class="ml-3 flex gap-2">
                  <button v-for="tab in ['cURL', 'JavaScript', 'Python']" :key="tab"
                    @click="activeTab = tab"
                    class="px-3 py-1 rounded-md text-xs font-mono transition-colors"
                    :class="activeTab === tab ? 'bg-white/10 text-white' : 'text-gray-500 hover:text-gray-300'">
                    {{ tab }}
                  </button>
                </div>
              </div>
              <div class="relative">
                <pre class="p-6 text-sm font-mono leading-relaxed text-gray-300 overflow-x-auto"><code v-html="codeExamples[activeTab]" /></pre>
                <button @click="copyCode" class="absolute top-4 right-4 px-3 py-1.5 rounded-lg bg-white/10 text-xs text-gray-300 hover:bg-white/20 transition-colors">
                  {{ copied ? 'Copiado!' : 'Copiar' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- FAQ -->
    <section class="py-24 bg-white dark:bg-gray-900">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 anim-el">
          <h2 class="section-title">Perguntas Frequentes</h2>
        </div>
        <div class="space-y-3">
          <div v-for="(faq, i) in faqs" :key="i" class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden anim-el">
            <button @click="toggleFaq(i)"
              class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
              <span class="font-semibold text-sm text-ango-navy dark:text-white">{{ faq.q }}</span>
              <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 transition-transform flex-shrink-0 ml-4"
                   :class="openFaq === i ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 max-h-0"
                        enter-to-class="opacity-100 max-h-40" leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 max-h-40" leave-to-class="opacity-0 max-h-0">
              <div v-if="openFaq === i" class="px-6 pb-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ faq.a }}</p>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </section>

    <!-- CONTACTO -->
    <section class="py-24 bg-gray-50 dark:bg-gray-950">
      <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center anim-el">
        <h2 class="section-title mb-6">Contacte-nos</h2>
        <p class="text-gray-500 dark:text-gray-400 mb-10">Tem dúvidas? Fale connosco.</p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
          <a href="tel:+244935603163" class="flex items-center justify-center gap-3 px-8 py-4 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-ango-red hover:shadow-lg transition-all">
            <Phone class="w-5 h-5 text-ango-red" />
            <div class="text-left">
              <p class="text-xs text-gray-400 dark:text-gray-500">Telefone</p>
              <p class="font-semibold text-ango-navy dark:text-white">935603163</p>
            </div>
          </a>
          <a href="mailto:info@angoapi.co.ao" class="flex items-center justify-center gap-3 px-8 py-4 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-ango-red hover:shadow-lg transition-all">
            <Mail class="w-5 h-5 text-ango-red" />
            <div class="text-left">
              <p class="text-xs text-gray-400 dark:text-gray-500">Email</p>
              <p class="font-semibold text-ango-navy dark:text-white">info@angoapi.co.ao</p>
            </div>
          </a>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, markRaw } from 'vue'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import {
  FileText,
  Plug,
  CreditCard,
  Send,
  Clock,
  Rocket,
  MapPin,
  Smartphone,
  KeyRound,
  MessageSquare,
  Mail,
  QrCode,
  Shield,
  Activity,
  FileSearch,
  Lock,
  Server,
  BookOpen,
  Zap,
  Webhook,
  Phone
} from 'lucide-vue-next'

gsap.registerPlugin(ScrollTrigger)

const activeTab = ref('cURL')
const copied = ref(false)
const openFaq = ref(null)

const howItWorks = [
  { icon: markRaw(FileText), title: 'Crie conta', desc: 'Registe-se gratuitamente', gradient: 'bg-ango-navy/10' },
  { icon: markRaw(Plug), title: 'Escolha API', desc: 'Selecione o serviço', gradient: 'bg-ango-navy/10' },
  { icon: markRaw(CreditCard), title: 'Faça pagamento', desc: 'Escolha o plano', gradient: 'bg-ango-navy/10' },
  { icon: markRaw(Send), title: 'Envie comprovativo', desc: 'Envie o recibo', gradient: 'bg-ango-navy/10' },
  { icon: markRaw(Clock), title: 'Aguarde aprovação', desc: 'Processamento rápido', gradient: 'bg-ango-navy/10' },
  { icon: markRaw(Rocket), title: 'Integre a API', desc: 'Comece a usar', gradient: 'bg-ango-navy/10' },
]

const apis = [
  { icon: markRaw(MapPin), name: 'Localização', desc: 'Valide e obtenha dados de localização por endereço ou coordenadas em Angola.', bg: 'bg-ango-navy/10' },
  { icon: markRaw(Smartphone), name: 'Telefone', desc: 'Validação e verificação de números de telefone angolanos em tempo real.', bg: 'bg-ango-navy/10' },
  { icon: markRaw(KeyRound), name: 'OTP', desc: 'Geração e verificação de códigos OTP para autenticação de dois fatores.', bg: 'bg-ango-navy/10' },
  { icon: markRaw(MessageSquare), name: 'SMS', desc: 'Envio de SMS transacionais e de marketing para redes angolanas.', bg: 'bg-ango-navy/10' },
  { icon: markRaw(Mail), name: 'Email', desc: 'Envio de emails transacionais com tracking e confirmação de entrega.', bg: 'bg-ango-navy/10' },
  { icon: markRaw(QrCode), name: 'QR Code', desc: 'Geração de QR codes personalizados para pagamentos e compartilhamento.', bg: 'bg-ango-navy/10' },
]

const plans = [
  { name: 'BI', desc: 'Validação de Bilhete de Identidade', price: '1.000', featured: false, cta: 'Escolher Plano', features: ['500 requisições/mês', 'Validação de BI', 'Dados básicos', 'Suporte por email'] },
  { name: 'NIF', desc: 'Validação de Número de Identificação Fiscal', price: '2.000', featured: false, cta: 'Escolher Plano', features: ['1.000 requisições/mês', 'Validação de NIF', 'Dados completos', 'Suporte prioritário', 'Webhooks'] },
  { name: 'BI + NIF', desc: 'Todos os serviços de validação', price: '3.000', featured: true, cta: 'Começar agora', features: ['2.000 requisições/mês', 'Validação de BI e NIF', 'Dados completos', 'Suporte 24/7', 'Webhooks', 'Dashboard avançado', 'Rate limits elevados'] },
]

const securityFeatures = [
  { icon: markRaw(KeyRound), title: 'API Keys', desc: 'Chaves únicas por utilizador com revogação imediata.' },
  { icon: markRaw(Activity), title: 'Rate Limiting', desc: 'Controlo de taxa de requisições por plano.' },
  { icon: markRaw(FileSearch), title: 'Logs', desc: 'Registo completo de todas as chamadas à API.' },
  { icon: markRaw(Lock), title: 'Autenticação', desc: 'Token Bearer seguro com expiração automática.' },
  { icon: markRaw(Server), title: 'Arquitectura Segura', desc: 'Infraestrutura com SSL e isolamento de dados.' },
]

const devFeatures = [
  { icon: markRaw(BookOpen), title: 'Documentação Clara', desc: 'Endpoints, parâmetros e exemplos completos' },
  { icon: markRaw(Zap), title: 'Resposta Rápida', desc: 'Latência média inferior a 200ms' },
  { icon: markRaw(Webhook), title: 'Webhooks', desc: 'Receba notificações em tempo real' },
]

const codeExamples = {
  'cURL': `<span class="text-ango-orange">curl</span> -X POST https://api.angoapi.co.ao/api/v1/validate/bi \\
  -H <span class="text-ango-gold">"Authorization: Bearer sk_live_abc123"</span> \\
  -H <span class="text-ango-gold">"Content-Type: application/json"</span> \\
  -d <span class="text-ango-gold">'{"bi_number": "001234567LA042"}'</span>`,
  'JavaScript': `<span class="text-ango-orange">const</span> response = <span class="text-ango-orange">await</span> <span class="text-green-300">fetch</span>(<span class="text-ango-gold">'/api/v1/validate/bi'</span>, {
  <span class="text-gray-500">method:</span> <span class="text-ango-gold">'POST'</span>,
  <span class="text-gray-500">headers:</span> {
    <span class="text-ango-gold">'Authorization'</span>: <span class="text-ango-gold">\`Bearer \${apiKey}\`</span>,
    <span class="text-ango-gold">'Content-Type'</span>: <span class="text-ango-gold">'application/json'</span>,
  },
  <span class="text-gray-500">body:</span> <span class="text-green-300">JSON.stringify</span>({ <span class="text-gray-500">bi_number:</span> <span class="text-ango-gold">'001234567LA042'</span> })
});
<span class="text-ango-orange">const</span> data = <span class="text-ango-orange">await</span> response.<span class="text-green-300">json</span>();`,
  'Python': `<span class="text-ango-orange">import</span> requests

response = requests.<span class="text-green-300">post</span>(
    <span class="text-ango-gold">'https://api.angoapi.co.ao/api/v1/validate/bi'</span>,
    <span class="text-gray-500">headers</span>={<span class="text-ango-gold">'Authorization'</span>: <span class="text-ango-gold">f'Bearer {api_key}'</span>},
    <span class="text-gray-500">json</span>={<span class="text-ango-gold">'bi_number'</span>: <span class="text-ango-gold">'001234567LA042'</span>}
)
data = response.<span class="text-green-300">json</span>()`,
}

const faqs = [
  { q: 'O que é a AngoAPI?', a: 'A AngoAPI é uma plataforma de APIs que permite a empresas e developers integrar serviços de validação e dados no ecossistema digital angolano, incluindo validação de BI, NIF, envio de SMS e mais.' },
  { q: 'Como começo a usar?', a: 'Crie uma conta gratuita, escolha o plano adequado ao seu negócio, efetue o pagamento e aguarde a aprovação. Depois disso, terá acesso às suas API Keys e poderá começar a integrar.' },
  { q: 'Quais são os métodos de pagamento aceites?', a: 'Aceitamos transferência bancária, Multicaixa Express e pagamento via referência. Após o pagamento, envie o comprovativo para aprovação.' },
  { q: 'Existe um plano gratuito?', a: 'Oferecemos um sandbox gratuito para testes e desenvolvimento. Para produção, é necessário subscrever um dos nossos planos pagos.' },
  { q: 'Qual é o tempo de resposta da API?', a: 'A maioria das nossas APIs responde em menos de 200ms. Temos infraestrutura optimizada para garantir alta disponibilidade e baixa latência.' },
  { q: 'Posso cancelar a minha subscrição?', a: 'Sim, pode cancelar a sua subscrição a qualquer momento. O acesso será mantido até ao final do período pago.' },
]

const toggleFaq = (i) => {
  openFaq.value = openFaq.value === i ? null : i
}

const copyCode = () => {
  const text = codeExamples[activeTab.value].replace(/<[^>]+>/g, '')
  navigator.clipboard.writeText(text)
  copied.value = true
  setTimeout(() => copied.value = false, 2000)
}

onMounted(() => {
  gsap.utils.toArray('.anim-el').forEach((el) => {
    gsap.from(el, {
      y: 40,
      opacity: 0,
      duration: 0.8,
      ease: 'power3.out',
      scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' },
    })
  })

  gsap.utils.toArray('.parallax-el').forEach((el) => {
    gsap.to(el, {
      y: -80,
      ease: 'none',
      scrollTrigger: { trigger: el.closest('section'), start: 'top bottom', end: 'bottom top', scrub: 1 },
    })
  })

  gsap.from('.hero-content', { x: -60, opacity: 0, duration: 1, ease: 'power3.out', delay: 0.2 })
  gsap.from('.hero-code', { x: 60, opacity: 0, duration: 1, ease: 'power3.out', delay: 0.4 })
})
</script>
