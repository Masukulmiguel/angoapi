<template>
  <div class="flex items-center justify-center" :class="containerClass">
    <div class="relative">
      <div class="w-10 h-10 border-4 border-gray-200 rounded-full animate-spin"
           :class="sizeClass" style="border-top-color: #E8453C;" />
    </div>
    <span v-if="text" class="ml-3 text-sm text-gray-500">{{ text }}</span>
  </div>
</template>

<script setup>
defineProps({
  text: { type: String, default: '' },
  size: { type: String, default: 'md' },
  containerClass: { type: String, default: 'py-12' },
})

const sizeClass = 'w-10 h-10'
</script>
