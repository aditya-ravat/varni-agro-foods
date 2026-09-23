<script setup lang="ts">
import { Thermometer, Droplet, Calendar } from 'lucide-vue-next'

const route = useRoute()
const { data: c } = await useApi<any>(`/commodities/${route.params.slug}`, {
  transform: (res: any) => res?.data ?? res,
})

if (!c.value) {
  throw createError({ statusCode: 404, statusMessage: 'Commodity not found' })
}

const com = computed(() => c.value)

useSeoMeta({
  title: com.value.seo?.title || `${com.value.name} Cold Storage`,
  description: com.value.seo?.description || com.value.short_description,
})
</script>

<template>
  <div v-if="com">
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container">
        <NuxtLink to="/products" class="text-brand-200 hover:text-white text-sm">← All commodities</NuxtLink>
        <h1 class="font-display text-3xl md:text-5xl font-extrabold text-white mt-3">{{ com.name }} Cold Storage</h1>
        <p class="text-brand-100 text-lg mt-4 max-w-2xl">{{ com.short_description }}</p>
      </div>
    </section>

    <section class="section">
      <div class="container grid lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8 space-y-6">
          <div class="grid sm:grid-cols-3 gap-4">
            <div class="card p-5 text-center">
              <Thermometer class="w-6 h-6 text-brand-700 mx-auto mb-2" />
              <div class="font-display text-xl font-extrabold text-slate-900">{{ com.recommended_temp_c.min }}° — {{ com.recommended_temp_c.max }}°C</div>
              <div class="text-xs text-slate-500 mt-1">Recommended temperature</div>
            </div>
            <div class="card p-5 text-center">
              <Droplet class="w-6 h-6 text-brand-700 mx-auto mb-2" />
              <div class="font-display text-xl font-extrabold text-slate-900">{{ com.recommended_humidity_pct.min }}% — {{ com.recommended_humidity_pct.max }}%</div>
              <div class="text-xs text-slate-500 mt-1">Recommended humidity</div>
            </div>
            <div class="card p-5 text-center">
              <Calendar class="w-6 h-6 text-brand-700 mx-auto mb-2" />
              <div class="font-display text-xl font-extrabold text-slate-900">{{ com.shelf_life_days }} days</div>
              <div class="text-xs text-slate-500 mt-1">Typical shelf life</div>
            </div>
          </div>
          <article v-if="com.body_html" v-html="com.body_html" class="prose prose-slate max-w-none" />
        </div>
        <aside class="lg:col-span-4">
          <div class="card p-6 sticky top-24">
            <h3 class="font-semibold text-slate-900 mb-2">Need {{ com.name }} cold storage?</h3>
            <p class="text-sm text-slate-600 mb-4">Get an indicative tariff in under 5 minutes.</p>
            <NuxtLink :to="`/get-quote?commodity=${com.slug}`" class="btn-primary w-full">Get a Quote</NuxtLink>
          </div>
        </aside>
      </div>
    </section>
  </div>
</template>
