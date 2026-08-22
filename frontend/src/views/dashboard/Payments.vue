<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ango-navy">Pagamentos</h1>
        <p class="text-gray-500 text-sm mt-1">Histórico de pagamentos e envio de comprovativos</p>
      </div>
      <button @click="showPay = true" class="btn-primary text-sm !px-5 !py-2.5">
        + Novo Pagamento
      </button>
    </div>

    <div v-if="store.loading" class="py-12"><LoadingSpinner /></div>

    <div v-else-if="store.list.length" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="text-left px-5 py-3 font-medium text-gray-500">Data</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500">Plano</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500">Valor</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500">Estado</th>
            <th class="text-left px-5 py-3 font-medium text-gray-500">Comprovativo</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="p in store.list" :key="p.id" class="hover:bg-gray-50">
            <td class="px-5 py-3 text-gray-600">{{ p.created_at || 'N/A' }}</td>
            <td class="px-5 py-3 font-medium text-ango-navy">{{ p.plan?.name || 'N/A' }}</td>
            <td class="px-5 py-3 text-gray-600">{{ p.amount ? p.amount.toLocaleString() + ' Kz' : 'N/A' }}</td>
            <td class="px-5 py-3">
              <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                :class="statusClass(p.status)">{{ p.status || 'pending' }}</span>
            </td>
            <td class="px-5 py-3">
              <span v-if="p.proof" class="text-green-600 text-xs font-medium">Enviado</span>
              <span v-else class="text-gray-400 text-xs">Pendente</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-else class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center">
      <div class="text-5xl mb-4">💰</div>
      <h3 class="text-lg font-bold text-ango-navy mb-2">Sem pagamentos</h3>
      <p class="text-gray-500 text-sm mb-6">Ainda não realizou nenhum pagamento.</p>
      <router-link to="/planos" class="btn-primary text-sm">Ver Planos</router-link>
    </div>

    <Teleport to="body">
      <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100"
                  leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showPay" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="showPay = false">
          <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-ango-navy mb-4">Novo Pagamento</h3>
            <form @submit.prevent="submitPayment" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Plano</label>
                <select v-model="payment.plan_id" required
                  class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-ango-red outline-none text-sm">
                  <option value="">Selecionar plano</option>
                  <option value="1">BI — 36.000 Kz</option>
                  <option value="2">NIF — 60.000 Kz</option>
                  <option value="3">BI+NIF — 120.000 Kz</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Método de pagamento</label>
                <select v-model="payment.method" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-ango-red outline-none text-sm">
                  <option value="transfer">Transferência Bancária</option>
                  <option value="express">Multicaixa Express</option>
                  <option value="reference">Referência</option>
                </select>
              </div>
              <div class="flex gap-3 justify-end">
                <button type="button" @click="showPay = false" class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700">Cancelar</button>
                <button type="submit" class="btn-primary text-sm !px-5 !py-2.5">Submeter</button>
              </div>
            </form>
          </div>
        </div>
      </transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usePaymentsStore } from '../../stores/payments'
import LoadingSpinner from '../../components/LoadingSpinner.vue'

const store = usePaymentsStore()
const showPay = ref(false)
const payment = ref({ plan_id: '', method: 'transfer' })

onMounted(() => store.fetchList())

const statusClass = (s) => ({
  approved: 'bg-green-100 text-green-700',
  pending: 'bg-yellow-100 text-yellow-700',
  rejected: 'bg-red-100 text-red-700',
}[s] || 'bg-gray-100 text-gray-500')

const submitPayment = async () => {
  await store.storePayment(payment.value)
  showPay.value = false
  payment.value = { plan_id: '', method: 'transfer' }
}
</script>
