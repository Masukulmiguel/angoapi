<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ango-navy">API Keys</h1>
        <p class="text-gray-500 text-sm mt-1">Gerir as suas chaves de acesso à API</p>
      </div>
      <button @click="showCreate = true" class="btn-primary text-sm !px-5 !py-2.5">
        + Nova Key
      </button>
    </div>

    <div v-if="store.loading" class="py-12"><LoadingSpinner /></div>

    <div v-else-if="store.list.length" class="space-y-3">
      <div v-for="key in store.list" :key="key.id"
        class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
        <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center text-lg">🔑</div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-1">
            <h4 class="font-semibold text-sm text-ango-navy">{{ key.name || 'API Key' }}</h4>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold"
              :class="key.revoked ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'">
              {{ key.revoked ? 'Revogada' : 'Activa' }}
            </span>
          </div>
          <p class="text-xs font-mono text-gray-400 truncate">
            {{ key.key ? key.key.slice(0, 12) + '••••••••' + key.key.slice(-4) : '••••••••' }}
          </p>
          <p class="text-xs text-gray-400 mt-1">Criada em {{ key.created_at || 'N/A' }}</p>
        </div>
        <div class="flex gap-2">
          <button @click="copyKey(key)" class="px-3 py-1.5 rounded-lg bg-gray-100 text-xs text-gray-600 hover:bg-gray-200 transition-colors">
            Copiar
          </button>
          <button v-if="!key.revoked" @click="revoke(key)" class="px-3 py-1.5 rounded-lg bg-red-50 text-xs text-red-600 hover:bg-red-100 transition-colors">
            Revogar
          </button>
        </div>
      </div>
    </div>

    <div v-else class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center">
      <div class="text-5xl mb-4">🔑</div>
      <h3 class="text-lg font-bold text-ango-navy mb-2">Sem API Keys</h3>
      <p class="text-gray-500 text-sm mb-6">Crie a sua primeira chave para começar a usar a API.</p>
      <button @click="showCreate = true" class="btn-primary text-sm">Criar API Key</button>
    </div>

    <Teleport to="body">
      <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0" enter-to-class="opacity-100"
                  leave-active-class="transition duration-150 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showCreate" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" @click.self="showCreate = false">
          <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-ango-navy mb-4">Criar Nova API Key</h3>
            <form @submit.prevent="create" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nome</label>
                <input v-model="newKey.name" type="text" required placeholder="Ex: Produção, Testes..."
                  class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-ango-red focus:ring-2 focus:ring-ango-red/20 outline-none transition-all text-sm" />
              </div>
              <div class="flex gap-3 justify-end">
                <button type="button" @click="showCreate = false" class="px-4 py-2.5 text-sm text-gray-500 hover:text-gray-700">Cancelar</button>
                <button type="submit" :disabled="creating" class="btn-primary text-sm !px-5 !py-2.5 disabled:opacity-50">
                  {{ creating ? 'A criar...' : 'Criar' }}
                </button>
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
import { useApiKeysStore } from '../../stores/apiKeys'
import LoadingSpinner from '../../components/LoadingSpinner.vue'

const store = useApiKeysStore()
const showCreate = ref(false)
const creating = ref(false)
const newKey = ref({ name: '' })

onMounted(() => store.fetchList())

const create = async () => {
  creating.value = true
  try {
    await store.createKey(newKey.value)
    showCreate.value = false
    newKey.value = { name: '' }
  } finally { creating.value = false }
}

const revoke = async (key) => {
  if (confirm('Tem certeza que deseja revogar esta key?')) {
    await store.revokeKey(key.id)
  }
}

const copyKey = (key) => {
  if (key.key) navigator.clipboard.writeText(key.key)
}
</script>
