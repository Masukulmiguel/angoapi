<template>
  <div class="fixed bottom-6 right-6 z-50">
    <transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0 scale-90 translate-y-4"
                enter-to-class="opacity-100 scale-100 translate-y-0" leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-90">
      <div v-if="open" class="mb-4 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col" style="height: 480px;">
        <div class="bg-ango-navy text-white p-4 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-ango-red flex items-center justify-center">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            </div>
            <div>
              <p class="font-semibold text-sm">AngoAPI Assistente</p>
              <p class="text-xs text-gray-300">Online agora</p>
            </div>
          </div>
          <button @click="open = false" class="p-1 hover:bg-white/10 rounded-lg transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
          <div v-for="(msg, i) in messages" :key="i" class="flex" :class="msg.role === 'user' ? 'justify-end' : 'justify-start'">
            <div class="max-w-[80%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed"
                 :class="msg.role === 'user' ? 'bg-ango-navy text-white rounded-br-md' : 'bg-white text-gray-800 shadow-sm border border-gray-100 rounded-bl-md'">
              {{ msg.content }}
            </div>
          </div>
          <div v-if="typing" class="flex justify-start">
            <div class="bg-white text-gray-400 shadow-sm border border-gray-100 rounded-2xl rounded-bl-md px-4 py-2.5 text-sm">
              <span class="inline-flex gap-1"><span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0ms"/><span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 150ms"/><span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 300ms"/></span>
            </div>
          </div>
        </div>

        <div class="p-3 border-t border-gray-200 bg-white">
          <form @submit.prevent="send" class="flex gap-2">
            <input v-model="input" type="text" placeholder="Escreva a sua mensagem..."
              class="flex-1 px-4 py-2.5 bg-gray-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-ango-red/50 transition-all" />
            <button type="submit" :disabled="!input.trim() || typing"
              class="w-10 h-10 rounded-xl bg-ango-red text-white flex items-center justify-center hover:bg-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            </button>
          </form>
        </div>
      </div>
    </transition>

    <button @click="open = !open"
      class="ml-auto w-14 h-14 rounded-full bg-ango-red text-white shadow-xl flex items-center justify-center hover:bg-red-600 transition-all hover:scale-105 active:scale-95">
      <svg v-if="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
      </svg>
      <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
</template>

<script setup>
import { ref, nextTick, watch } from 'vue'
import api from '../plugins/axios'

const open = ref(false)
const input = ref('')
const messages = ref([
  { role: 'assistant', content: 'Olá! Sou o assistente virtual da AngoAPI. Como posso ajudar?' }
])
const typing = ref(false)
const messagesContainer = ref(null)

const scrollToBottom = () => {
  nextTick(() => {
    if (messagesContainer.value) {
      messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
    }
  })
}

watch(messages, scrollToBottom, { deep: true })

const send = async () => {
  const text = input.value.trim()
  if (!text) return
  input.value = ''
  messages.value.push({ role: 'user', content: text })
  typing.value = true

  try {
    const { data } = await api.post('/chat', { message: text })
    messages.value.push({ role: 'assistant', content: data.reply || data.message || data.response || 'Desculpe, não consegui processar.' })
  } catch {
    messages.value.push({ role: 'assistant', content: 'Desculpe, ocorreu um erro. Tente novamente.' })
  } finally {
    typing.value = false
  }
}
</script>
