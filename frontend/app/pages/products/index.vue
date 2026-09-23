<script setup lang="ts">
import { ArrowRight } from 'lucide-vue-next'

useSeoMeta({
  title: 'Commodities We Store | Varni Agro Foods',
  description: 'Cold storage for potato, onion, apple, mango, banana, frozen vegetables, dairy and pharma — with the right temperature for every commodity.',
})

const { data: res } = await useApi<{ data: any[] }>('/commodities')
const commodities = computed(() => res.value?.data ?? [])
</script>

<template>
  <div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container">
        <p class="section-eyebrow text-brand-200">What we store</p>
        <h1 class="section-title text-white">Commodities we specialise in</h1>
        <p class="text-brand-100 mt-4 max-w-2xl">Every chamber is tuned to the specific needs of the commodity it holds.</p>
      </div>
    </section>
    <section class="section">
      <div class="container grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        <NuxtLink v-for="c in commodities" :key="c.id" :to="`/products/${c.slug}`"
          class="card p-6 hover:shadow-lg hover:-translate-y-0.5 transition group">
          <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-700 grid place-items-center mb-4 text-2xl">🥬</div>
          <h2 class="font-semibold text-slate-900">{{ c.name }}</h2>
          <p class="text-xs text-slate-500 mt-1">{{ c.recommended_temp_c.min }}°C – {{ c.recommended_temp_c.max }}°C · {{ c.recommended_humidity_pct.min }}–{{ c.recommended_humidity_pct.max }}% RH</p>
          <p v-if="c.short_description" class="text-sm text-slate-600 mt-3 line-clamp-2">{{ c.short_description }}</p>
          <p class="text-sm font-semibold text-brand-700 mt-4 inline-flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
            Read more <ArrowRight class="w-3.5 h-3.5" />
          </p>
        </NuxtLink>
      </div>
    </section>
  </div>
</template>
