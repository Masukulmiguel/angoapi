import { ref, watchEffect, onMounted } from 'vue'

const isDark = ref(false)

export function useTheme() {
  onMounted(() => {
    const stored = localStorage.getItem('angoapi_theme')
    if (stored) {
      isDark.value = stored === 'dark'
    } else {
      isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    }
    applyTheme()
  })

  watchEffect(() => {
    applyTheme()
  })

  function applyTheme() {
    const root = document.documentElement
    if (isDark.value) {
      root.classList.add('dark')
    } else {
      root.classList.remove('dark')
    }
  }

  function toggle() {
    isDark.value = !isDark.value
    localStorage.setItem('angoapi_theme', isDark.value ? 'dark' : 'light')
    applyTheme()
  }

  function set(dark) {
    isDark.value = dark
    localStorage.setItem('angoapi_theme', dark ? 'dark' : 'light')
    applyTheme()
  }

  return { isDark, toggle, set }
}
