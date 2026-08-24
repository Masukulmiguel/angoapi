<template>
  <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
       :class="scrolled || !$route.path.match(/^\/?$/) ? 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-md shadow-sm dark:shadow-gray-800/50' : 'bg-transparent'"
       @mouseenter="() => {}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 md:h-20">
        <router-link to="/" class="flex items-center">
          <template v-if="isDark">
            <img src="/logo-fundo-escuro.png" alt="AngoAPI" class="h-14 md:h-16 w-auto" />
          </template>
          <template v-else>
            <img v-if="isHome && !scrolled" src="/logo-fundo-escuro.png" alt="AngoAPI" class="h-14 md:h-16 w-auto" />
            <img v-else src="/logo.png" alt="AngoAPI" class="h-14 md:h-16 w-auto" />
          </template>
        </router-link>

        <div class="hidden md:flex items-center gap-8">
          <router-link v-for="link in navLinks" :key="link.to" :to="link.to"
            class="text-sm font-medium transition-colors hover:text-ango-red"
            :class="isHome && !scrolled ? 'text-gray-300 hover:text-white' : 'text-gray-600 dark:text-gray-300'">
            {{ link.label }}
          </router-link>
          <ThemeToggle />
          <router-link to="/register" class="btn-primary text-sm !px-5 !py-2.5">
            Começar agora
          </router-link>
        </div>

        <div class="flex items-center gap-2 md:hidden">
          <ThemeToggle />
          <button @click="mobileOpen = !mobileOpen" class="w-10 h-10 flex flex-col items-center justify-center gap-1.5 rounded-lg"
                  :class="isHome && !scrolled ? 'text-white' : 'text-ango-navy dark:text-white'">
            <span :class="['block w-5 h-0.5 rounded-full bg-current transition-all duration-300', mobileOpen && 'rotate-45 translate-y-[4px]']"></span>
            <span :class="['block w-5 h-0.5 rounded-full bg-current transition-all duration-300', mobileOpen && 'opacity-0 scale-0']"></span>
            <span :class="['block w-5 h-0.5 rounded-full bg-current transition-all duration-300', mobileOpen && '-rotate-45 -translate-y-[4px]']"></span>
          </button>
        </div>
      </div>
    </div>

    <transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0" leave-active-class="transition duration-150 ease-in"
                leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
      <div v-if="mobileOpen" class="md:hidden bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shadow-lg">
        <div class="px-4 py-4 space-y-2">
          <router-link v-for="link in navLinks" :key="link.to" :to="link.to"
            @click="mobileOpen = false"
            class="block px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800">
            {{ link.label }}
          </router-link>
          <router-link to="/register" @click="mobileOpen = false"
            class="block text-center btn-primary text-sm mt-2">
            Começar agora
          </router-link>
        </div>
      </div>
    </transition>
  </nav>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute } from 'vue-router'
import ThemeToggle from './ThemeToggle.vue'
import { useTheme } from '../composables/useTheme'

const route = useRoute()
const scrolled = ref(false)
const mobileOpen = ref(false)
const { isDark } = useTheme()

const isHome = computed(() => route.path === '/' || route.path === '')

const navLinks = [
  { to: '/', label: 'Home' },
  { to: '/try-it', label: 'Testar' },
  { to: '/planos', label: 'Planos' },
  { to: '/docs', label: 'Docs' },
  { to: '/login', label: 'Login' },
]

const onScroll = () => {
  scrolled.value = window.scrollY > 20
}

onMounted(() => window.addEventListener('scroll', onScroll))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>
