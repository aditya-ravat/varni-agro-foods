<script setup lang="ts">
import { Snowflake, Wind, Truck, Leaf, Thermometer, ArrowRight } from 'lucide-vue-next'

useSeoMeta({
  title: 'Cold Chain Services | Varni Agro Foods',
  description: 'Cold storage, controlled atmosphere, blast freezing, ripening chambers and reefer transport — end-to-end cold chain services for India.',
})

const { data: res } = await useApi<{ data: any[] }>('/services')
const services = computed(() => res.value?.data ?? [])

const iconMap: Record<string, any> = {
  snowflake: Snowflake, wind: Wind, leaf: Leaf, truck: Truck, thermometer: Thermometer,
}
function iconFor(name?: string) { return name && iconMap[name] ? iconMap[name] : Snowflake }
</script>

<template>
  <div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container">
        <p class="section-eyebrow text-brand-200">What we do</p>
        <h1 class="section-title text-white">End-to-end cold chain services</h1>
        <p class="text-brand-100 mt-4 max-w-2xl">From farm to fork, we handle every link of the cold chain so you can focus on growing your business.</p>
      </div>
    </section>
    <section class="section">
      <div class="container grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        <NuxtLink v-for="s in services" :key="s.slug" :to="`/services/${s.slug}`" class="card p-6 hover:shadow-lg transition group">
          <div class="w-12 h-12 rounded-xl bg-ice-50 text-ice-600 grid place-items-center mb-4 group-hover:bg-brand-700 group-hover:text-white transition">
            <component :is="iconFor(s.icon)" class="w-6 h-6" />
          </div>
          <h2 class="font-display text-xl font-bold text-slate-900 mb-2">{{ s.name }}</h2>
          <p class="text-sm text-slate-600 leading-relaxed">{{ s.short_description }}</p>
          <p class="text-sm font-semibold text-brand-700 mt-4 inline-flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
            Learn more <ArrowRight class="w-3.5 h-3.5" />
          </p>
        </NuxtLink>
      </div>
    </section>
  </div>
</template>
