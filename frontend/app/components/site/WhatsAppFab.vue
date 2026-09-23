<script setup lang="ts">
import { MessageCircle } from 'lucide-vue-next'
const config = useRuntimeConfig()
const phone = (config.public.whatsapp as string).replace(/[^0-9]/g, '')
const text = encodeURIComponent('Hello Varni Agro Foods, I am enquiring about cold storage.')
</script>
<template>
  <a :href="`https://wa.me/${phone}?text=${text}`" target="_blank" rel="noopener"
    class="fixed bottom-5 right-5 z-30 inline-flex items-center gap-2 px-4 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg font-semibold"
    aria-label="WhatsApp us"
  >
    <MessageCircle class="w-5 h-5" />
    <span class="hidden sm:inline">{{ $t('cta.whatsapp') }}</span>
  </a>
</template>
