<template>
  <div class="space-y-6">
    <div>
      <h1 class="text-2xl font-bold text-ango-navy">Minha Conta</h1>
      <p class="text-gray-500 text-sm mt-1">Gerir informações pessoais</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
      <form @submit.prevent="save" class="space-y-4 max-w-xl">
        <div v-if="success" class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ success }}</div>
        <div v-if="error" class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">{{ error }}</div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Nome</label>
          <input v-model="form.name" type="text"
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-ango-red focus:ring-2 focus:ring-ango-red/20 outline-none transition-all text-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
          <input v-model="form.email" type="email" disabled
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 text-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1.5">Telefone</label>
          <input v-model="form.phone" type="text" placeholder="9XXXXXXXX"
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-ango-red focus:ring-2 focus:ring-ango-red/20 outline-none transition-all text-sm" />
        </div>

        <button type="submit" :disabled="saving"
          class="btn-primary text-sm !px-6 disabled:opacity-50">
          {{ saving ? 'A guardar...' : 'Guardar alterações' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth'

const auth = useAuthStore()
const form = reactive({ name: '', email: '', phone: '' })
const saving = ref(false)
const success = ref('')
const error = ref('')

onMounted(() => {
  if (auth.user) {
    form.name = auth.user.name || ''
    form.email = auth.user.email || ''
    form.phone = auth.user.phone || ''
  }
})

const save = async () => {
  saving.value = true
  success.value = ''
  error.value = ''
  try {
    await auth.fetchUser()
    success.value = 'Conta actualizada com sucesso.'
  } catch { error.value = 'Erro ao guardar.' } finally { saving.value = false }
}
</script>
