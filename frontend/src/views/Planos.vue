<template>
  <div class="pt-24 pb-16 bg-gray-50 dark:bg-gray-950 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-ango-gold/10 text-sm font-medium text-ango-orange mb-4">
          Pagamento mensal
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-ango-navy dark:text-white mb-4">Planos e Preços</h1>
        <p class="text-lg text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">Escolha o plano ideal para o seu negócio. APIs de validação, comunicação e mais. Sandbox gratuito para testes.</p>
      </div>

      <div class="lg:hidden mb-4">
        <p class="text-center text-gray-400 dark:text-gray-500 text-xs">Deslize para ver todos os planos</p>
      </div>

      <div class="flex lg:grid lg:grid-cols-4 gap-6 max-w-6xl mx-auto overflow-x-auto snap-x snap-mandatory pb-4 lg:pb-0 -mx-4 px-4 lg:mx-0 lg:px-0 scrollbar-hide" ref="carousel">
        <div v-for="(plan, i) in plans" :key="i"
          class="relative rounded-2xl p-6 sm:p-8 transition-all duration-300 snap-center flex-shrink-0 w-[85vw] sm:w-[70vw] md:w-[45vw] lg:w-auto lg:flex-shrink"
          :class="plan.featured ? 'bg-ango-navy text-white shadow-2xl lg:scale-105 border-2 border-ango-gold' : 'bg-white dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-800 shadow-lg hover:shadow-xl'">
          <div v-if="plan.featured" class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-ango-gold text-ango-dark text-xs font-bold whitespace-nowrap">
            MAIS POPULAR
          </div>
          <div class="mb-6">
            <h3 class="text-xl font-bold mb-2" :class="plan.featured ? 'text-white' : 'text-ango-navy dark:text-white'">{{ plan.name }}</h3>
            <p class="text-sm" :class="plan.featured ? 'text-gray-300' : 'text-gray-500'">{{ plan.desc }}</p>
          </div>
          <div class="mb-8">
            <span class="text-4xl font-extrabold" :class="plan.featured ? 'text-white' : 'text-ango-navy dark:text-white'">{{ plan.price }}</span>
            <span class="text-sm ml-1" :class="plan.featured ? 'text-gray-300' : 'text-gray-500'">Kz/mês</span>
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

      <div class="lg:hidden flex justify-center gap-2 mt-6">
        <button v-for="(_, i) in plans" :key="i" @click="scrollTo(i)"
          class="w-2 h-2 rounded-full transition-all duration-300"
          :class="activeIndex === i ? 'bg-ango-navy dark:bg-white w-6' : 'bg-gray-300 dark:bg-gray-600'" />
      </div>

      <div class="mt-16 text-center">
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">Precisa de algo personalizado?</p>
        <a href="mailto:info@angoapi.co.ao" class="text-ango-red font-semibold text-sm hover:underline">Contacte-nos para um plano personalizado →</a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const carousel = ref(null)
const activeIndex = ref(0)

const scrollTo = (index) => {
  if (!carousel.value) return
  const children = carousel.value.children
  if (children[index]) {
    children[index].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' })
    activeIndex.value = index
  }
}

const onScroll = () => {
  if (!carousel.value) return
  const el = carousel.value
  const scrollLeft = el.scrollLeft
  const cardWidth = el.children[0]?.offsetWidth || 1
  const gap = 24
  activeIndex.value = Math.round(scrollLeft / (cardWidth + gap))
}

onMounted(() => {
  carousel.value?.addEventListener('scroll', onScroll, { passive: true })
})

onUnmounted(() => {
  carousel.value?.removeEventListener('scroll', onScroll)
})

const plans = [
  {
    name: 'Starter',
    desc: 'Para quem está a começar',
    price: '500',
    featured: false,
    cta: 'Escolher Plano',
    features: [
      '100 requisições/mês',
      'Validação de BI',
      'Validação de Telefone',
      'Dados básicos',
      'Sandbox para testes',
      'Documentação completa',
    ],
  },
  {
    name: 'Essencial',
    desc: 'Ideal para pequenas empresas',
    price: '2.000',
    featured: false,
    cta: 'Escolher Plano',
    features: [
      '500 requisições/mês',
      'Validação de BI e NIF',
      'Validação de Telefone',
      'Envio de SMS',
      'Dados completos',
      'Suporte por email',
      'Sandbox para testes',
      'Dashboard básico',
    ],
  },
  {
    name: 'Profissional',
    desc: 'Para negócios em crescimento',
    price: '5.000',
    featured: true,
    cta: 'Começar agora',
    features: [
      '2.000 requisições/mês',
      'Validação de BI e NIF',
      'Validação de Telefone',
      'Envio de SMS e Email',
      'OTP (Gerar e Verificar)',
      'Geração de QR Code',
      'Verificação de Identidade',
      'Suporte prioritário',
      'Webhooks',
      'Dashboard avançado',
      'Rate limits elevados',
    ],
  },
  {
    name: 'Enterprise',
    desc: 'Para grandes volumes',
    price: '15.000',
    featured: false,
    cta: 'Contactar Vendas',
    features: [
      '10.000 requisições/mês',
      'Todas as APIs disponíveis',
      'Validação de BI, NIF, Telefone',
      'SMS, Email, OTP, QR Code',
      'Verificação de Identidade',
      'Localização (Províncias/Municípios)',
      'Suporte 24/7',
      'Webhooks personalizados',
      'SLA garantido',
      'Rate limits ilimitados',
      'Conta dedicada',
    ],
  },
]
</script>

<style scoped>
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>
