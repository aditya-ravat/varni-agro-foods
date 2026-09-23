<script setup lang="ts">
import { Menu, X, Phone } from 'lucide-vue-next'

const { t, locale, locales, setLocale } = useI18n()
const config = useRuntimeConfig()
const open = ref(false)
const route = useRoute()

watch(() => route.fullPath, () => (open.value = false))

const nav = computed(() => [
  { to: '/', label: t('nav.home') },
  { to: '/services', label: t('nav.services') },
  { to: '/facilities', label: t('nav.facilities') },
  { to: '/products', label: t('nav.products') },
  { to: '/pricing', label: t('nav.pricing') },
  { to: '/blog', label: t('nav.blog') },
  { to: '/about', label: t('nav.about') },
  { to: '/contact', label: t('nav.contact') },
])
</script>

<template>
  <header class="sticky top-0 z-40 backdrop-blur bg-white/85 border-b border-slate-100">
    <div class="container flex items-center justify-between gap-6 h-16">
      <NuxtLink to="/" class="flex items-center gap-2.5">
        <span class="grid place-items-center w-9 h-9 rounded-xl bg-brand-700 text-white font-display font-extrabold">V</span>
        <span class="hidden sm:flex flex-col leading-tight">
          <span class="font-display font-bold text-brand-800 text-[15px]">Varni Agro Foods</span>
          <span class="text-[11px] text-slate-500">Cold Chain · Warehousing · Logistics</span>
        </span>
      </NuxtLink>

      <nav class="hidden lg:flex items-center gap-1">
        <NuxtLink
          v-for="item in nav" :key="item.to" :to="item.to"
          class="px-3.5 py-2 rounded-lg text-sm font-medium text-slate-700 hover:text-brand-700 hover:bg-brand-50 transition"
          active-class="text-brand-700 bg-brand-50"
        >{{ item.label }}</NuxtLink>
      </nav>

      <div class="flex items-center gap-2">
        <a :href="`tel:${config.public.contactPhone}`" class="hidden md:inline-flex items-center gap-2 text-sm font-semibold text-brand-700 hover:text-brand-800">
          <Phone class="w-4 h-4" /> {{ config.public.contactPhone }}
        </a>

        <select
          :value="locale"
          @change="(e) => setLocale(((e.target as HTMLSelectElement).value as any))"
          class="hidden md:block text-xs font-medium border border-slate-200 rounded-lg px-2 py-1.5 bg-white"
          aria-label="Language"
        >
          <option v-for="l in (locales as any[])" :key="l.code" :value="l.code">{{ l.name }}</option>
        </select>

        <NuxtLink to="/get-quote" class="hidden sm:inline-flex btn-primary text-sm">{{ $t('nav.getQuote') }}</NuxtLink>

        <button class="lg:hidden inline-grid place-items-center w-10 h-10 rounded-lg border border-slate-200" @click="open = !open" aria-label="Menu">
          <component :is="open ? X : Menu" class="w-5 h-5" />
        </button>
      </div>
    </div>

    <Transition enter-active-class="transition" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0">
      <div v-if="open" class="lg:hidden border-t border-slate-100 bg-white">
        <nav class="container py-4 flex flex-col gap-1">
          <NuxtLink v-for="item in nav" :key="item.to" :to="item.to"
            class="px-3 py-2.5 rounded-lg text-sm font-medium text-slate-700 hover:bg-brand-50"
            active-class="text-brand-700 bg-brand-50">
            {{ item.label }}
          </NuxtLink>
          <NuxtLink to="/get-quote" class="btn-primary mt-2">{{ $t('nav.getQuote') }}</NuxtLink>
        </nav>
      </div>
    </Transition>
  </header>
</template>
