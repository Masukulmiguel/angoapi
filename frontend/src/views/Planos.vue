<template>
  <div class="pt-24 pb-16 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-ango-gold/10 text-sm font-medium text-ango-orange mb-4">
          Pagamento anual
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-ango-navy mb-4">Planos e Preços</h1>
        <p class="text-lg text-gray-500 max-w-2xl mx-auto">Escolha o plano ideal para o seu negócio. Todos os planos incluem sandbox gratuito para testes.</p>
      </div>

      <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
        <div v-for="(plan, i) in plans" :key="i"
          class="relative rounded-2xl p-8 transition-all duration-300 hover:-translate-y-2"
          :class="plan.featured ? 'bg-ango-navy text-white shadow-2xl scale-105 border-2 border-ango-gold' : 'bg-white border-2 border-gray-100 shadow-lg hover:shadow-xl'">
          <div v-if="plan.featured" class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-ango-gold text-ango-dark text-xs font-bold">
            MAIS POPULAR
          </div>
          <div class="mb-6">
            <h3 class="text-xl font-bold mb-2" :class="plan.featured ? 'text-white' : 'text-ango-navy'">{{ plan.name }}</h3>
            <p class="text-sm" :class="plan.featured ? 'text-gray-300' : 'text-gray-500'">{{ plan.desc }}</p>
          </div>
          <div class="mb-8">
            <span class="text-4xl font-extrabold" :class="plan.featured ? 'text-white' : 'text-ango-navy'">{{ plan.price }}</span>
            <span class="text-sm ml-1" :class="plan.featured ? 'text-gray-300' : 'text-gray-500'">Kz/ano</span>
          </div>
          <ul class="space-y-3 mb-8">
            <li v-for="(f, fi) in plan.features" :key="fi" class="flex items-start gap-3 text-sm">
              <svg class="w-5 h-5 flex-shrink-0 mt-0.5" :class="plan.featured ? 'text-ango-gold' : 'text-green-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              <span :class="plan.featured ? 'text-gray-200' : 'text-gray-600'">{{ f }}</span>
            </li>
          </ul>
          <router-link to="/register" class="block text-center py-3 rounded-lg font-semibold text-sm transition-all"
            :class="plan.featured ? 'bg-ango-gold text-ango-dark hover:bg-yellow-400' : 'bg-ango-navy text-white hover:bg-ango-navy/90'">
            {{ plan.cta }}
          </router-link>
        </div>
      </div>

      <div class="mt-16 text-center">
        <p class="text-gray-500 text-sm mb-4">Precisa de algo personalizado?</p>
        <a href="mailto:info@angoapi.co.ao" class="text-ango-red font-semibold text-sm hover:underline">Contacte-nos para um plano personalizado →</a>
      </div>
    </div>
  </div>
</template>

<script setup>
const plans = [
  { name: 'BI', desc: 'Validação de Bilhete de Identidade', price: '36.000', featured: false, cta: 'Escolher Plano', features: ['1.000 requisições/mês', 'Validação de BI', 'Dados básicos do titular', 'Suporte por email', 'Sandbox para testes', 'Documentação completa'] },
  { name: 'NIF', desc: 'Validação de NIF', price: '60.000', featured: false, cta: 'Escolher Plano', features: ['2.000 requisições/mês', 'Validação de NIF', 'Dados completos da empresa', 'Suporte prioritário', 'Webhooks', 'Sandbox para testes'] },
  { name: 'BI + NIF', desc: 'Todos os serviços de validação', price: '120.000', featured: true, cta: 'Começar agora', features: ['5.000 requisições/mês', 'Validação de BI e NIF', 'Dados completos', 'Suporte 24/7', 'Webhooks', 'Dashboard avançado', 'Rate limits elevados', 'SLA garantido'] },
]
</script>
