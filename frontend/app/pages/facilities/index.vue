<script setup lang="ts">
import { MapPin, ArrowRight } from 'lucide-vue-next'

useSeoMeta({
  title: 'Our Cold Storage Facilities | Varni Agro Foods',
  description: 'Explore Varni Agro Foods\' network of FSSAI-certified cold storage facilities across Gujarat and Maharashtra.',
})

const { data: res } = await useApi<{ data: any[] }>('/facilities')
const facilities = computed(() => res.value?.data ?? [])
</script>

<template>
  <div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container">
        <p class="section-eyebrow text-brand-200">Our Network</p>
        <h1 class="section-title text-white">Strategically located cold storage facilities</h1>
        <p class="text-brand-100 mt-4 max-w-2xl">All facilities are FSSAI-certified, IoT-monitored, and designed for multi-temperature commodities.</p>
      </div>
    </section>

    <section class="section">
      <div class="container grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        <NuxtLink v-for="f in facilities" :key="f.id" :to="`/facilities/${f.slug}`"
          class="card overflow-hidden hover:shadow-lg transition">
          <div class="h-44 bg-gradient-to-br from-brand-200 to-brand-500 flex items-end p-5">
            <span class="pill bg-white text-brand-800">{{ f.address.city }}, {{ f.address.state }}</span>
          </div>
          <div class="p-6">
            <h2 class="font-display text-xl font-bold text-slate-900 mb-1">{{ f.name }}</h2>
            <p class="text-sm text-slate-500 mb-4">{{ f.tagline }}</p>
            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
              <MapPin class="w-4 h-4 text-brand-700" />
              <span>{{ f.address.line1 }}, {{ f.address.city }}</span>
            </div>
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
              <div>
                <div class="text-xl font-display font-extrabold text-brand-700">{{ Math.round(f.capacity_mt).toLocaleString() }} MT</div>
                <div class="text-[11px] uppercase tracking-wide text-slate-500">capacity</div>
              </div>
              <span class="text-sm font-semibold text-brand-700 inline-flex items-center gap-1.5">View <ArrowRight class="w-3.5 h-3.5" /></span>
            </div>
          </div>
        </NuxtLink>
      </div>
    </section>
  </div>
</template>
