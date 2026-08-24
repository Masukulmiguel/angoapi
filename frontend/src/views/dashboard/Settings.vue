<template>
  <div class="space-y-6 dark:bg-gray-900 dark:text-white">
    <div>
      <h1 class="text-2xl font-bold text-ango-navy dark:text-white">Configurações</h1>
      <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Segurança e preferências da conta</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 max-w-xl">
      <h3 class="font-semibold text-ango-navy dark:text-white mb-4">Alterar Password</h3>
      <form @submit.prevent="changePassword" class="space-y-4">
        <div v-if="success" class="p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ success }}</div>
        <div v-if="error" class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">{{ error }}</div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Password actual</label>
          <input v-model="pw.current_password" type="password" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-ango-red focus:ring-2 focus:ring-ango-red/20 outline-none transition-all text-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nova password</label>
          <input v-model="pw.password" type="password" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-ango-red focus:ring-2 focus:ring-ango-red/20 outline-none transition-all text-sm" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Confirmar nova password</label>
          <input v-model="pw.password_confirmation" type="password" required
            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:border-ango-red focus:ring-2 focus:ring-ango-red/20 outline-none transition-all text-sm" />
        </div>

        <button type="submit" :disabled="saving" class="btn-primary text-sm !px-6 disabled:opacity-50">
          {{ saving ? 'A alterar...' : 'Alterar password' }}
        </button>
      </form>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 max-w-xl">
      <h3 class="font-semibold text-ango-navy dark:text-white mb-4">Sessões Activas</h3>
      <div class="space-y-3">
        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
          <div>
            <p class="text-sm font-medium text-ango-navy dark:text-white">Sessão actual</p>
            <p class="text-xs text-gray-400 dark:text-gray-400">Navegador actual • Agora</p>
          </div>
          <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Activa</span>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-red-200 dark:border-red-800 shadow-sm p-6 max-w-xl">
      <h3 class="font-semibold text-red-600 dark:text-red-400 mb-2">Zona de perigo</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Apagar a conta irá remover todos os seus dados permanentemente.</p>
      <button class="px-4 py-2.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-sm font-medium rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors">
        Apagar conta
      </button>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import api from '../../plugins/axios'

const pw = reactive({ current_password: '', password: '', password_confirmation: '' })
const saving = ref(false)
const success = ref('')
const error = ref('')

const changePassword = async () => {
  saving.value = true
  success.value = ''
  error.value = ''
  try {
    await api.put('/password', pw)
    success.value = 'Password alterada com sucesso.'
    pw.current_password = ''
    pw.password = ''
    pw.password_confirmation = ''
  } catch (e) {
    error.value = e.response?.data?.message || 'Erro ao alterar password.'
  } finally { saving.value = false }
}
</script>
