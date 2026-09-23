<script setup lang="ts">
import { MapPin, Phone, Mail, Clock, Snowflake, Thermometer } from 'lucide-vue-next'

const route = useRoute()
const slug = route.params.slug as string

const { data: facility } = await useApi<any>(`/facilities/${slug}`, {
  transform: (res: any) => res?.data ?? res,
})

if (!facility.value) {
  throw createError({ statusCode: 404, statusMessage: 'Facility not found' })
}

const f = computed(() => facility.value)

useSeoMeta({
  title: f.value.seo?.title || f.value.name,
  description: f.value.seo?.description || f.value.description,
  ogImage: f.value.seo?.og_image,
})

useSchemaOrg([
  defineLocalBusiness({
    name: f.value.name,
    image: f.value.hero_image,
    address: {
      streetAddress: f.value.address.line1,
      addressLocality: f.value.address.city,
      addressRegion: f.value.address.state,
      postalCode: f.value.address.pincode,
      addressCountry: f.value.address.country,
    },
    telephone: f.value.contact.phone,
    email: f.value.contact.email,
    geo: f.value.location.lat ? {
      latitude: f.value.location.lat,
      longitude: f.value.location.lng,
    } : undefined,
  }),
])
</script>

<template>
  <div v-if="f">
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container grid lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
          <span class="pill bg-white/15 text-white">{{ f.address.city }}, {{ f.address.state }}</span>
          <h1 class="font-display text-3xl md:text-5xl font-extrabold text-white mt-4">{{ f.name }}</h1>
          <p class="text-brand-100 text-lg mt-4 max-w-2xl">{{ f.tagline }}</p>
        </div>
        <div class="lg:col-span-4 card p-6 bg-white/10 backdrop-blur border-white/15 text-white">
          <div class="text-4xl font-display font-extrabold">{{ Math.round(f.capacity_mt).toLocaleString() }} MT</div>
          <div class="text-sm uppercase tracking-wide text-brand-200">Total capacity</div>
          <div class="mt-4 pt-4 border-t border-white/20 grid grid-cols-2 gap-4 text-sm">
            <div>
              <div class="text-2xl font-bold">{{ f.chambers?.length || 0 }}</div>
              <div class="text-xs text-brand-200">Chambers</div>
            </div>
            <div>
              <div class="text-2xl font-bold">24×7</div>
              <div class="text-xs text-brand-200">Monitoring</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container grid lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8 space-y-6">
          <h2 class="section-title text-2xl md:text-3xl">About this facility</h2>
          <p class="text-slate-700 leading-relaxed">{{ f.description }}</p>
          <div v-if="f.body_html" v-html="f.body_html" class="prose max-w-none prose-slate" />

          <h3 class="font-display text-xl font-bold text-slate-900 mt-10">Chambers</h3>
          <div class="grid sm:grid-cols-2 gap-4">
            <div v-for="c in f.chambers" :key="c.code" class="card p-5">
              <div class="flex items-center justify-between mb-2">
                <span class="font-semibold text-slate-900">{{ c.name }}</span>
                <span class="pill" :class="c.type === 'freezer' ? 'bg-ice-100 text-ice-700' : 'bg-brand-50 text-brand-700'">
                  <Snowflake class="w-3 h-3" /> {{ c.type.replace('_', ' ') }}
                </span>
              </div>
              <div class="grid grid-cols-2 gap-3 text-sm text-slate-600 mt-3">
                <div class="flex items-center gap-1.5"><Thermometer class="w-3.5 h-3.5 text-brand-600" /> {{ c.temp_min_c }}° to {{ c.temp_max_c }}°C</div>
                <div>{{ Math.round(c.capacity_mt).toLocaleString() }} MT</div>
              </div>
            </div>
          </div>
        </div>

        <aside class="lg:col-span-4 space-y-4">
          <div class="card p-6 sticky top-24">
            <h3 class="font-semibold text-slate-900 mb-4">Reach this facility</h3>
            <ul class="space-y-3 text-sm">
              <li class="flex items-start gap-2.5"><MapPin class="w-4 h-4 mt-0.5 text-brand-700" />
                <span>{{ f.address.line1 }}, {{ f.address.city }}, {{ f.address.state }} {{ f.address.pincode }}</span>
              </li>
              <li><a class="flex items-center gap-2.5 text-slate-700 hover:text-brand-700" :href="`tel:${f.contact.phone}`"><Phone class="w-4 h-4 text-brand-700" /> {{ f.contact.phone }}</a></li>
              <li><a class="flex items-center gap-2.5 text-slate-700 hover:text-brand-700" :href="`mailto:${f.contact.email}`"><Mail class="w-4 h-4 text-brand-700" /> {{ f.contact.email }}</a></li>
              <li v-if="f.opening_hours" class="flex items-start gap-2.5">
                <Clock class="w-4 h-4 mt-0.5 text-brand-700" />
                <div>
                  <div v-for="(hours, day) in f.opening_hours" :key="day"><span class="font-medium uppercase text-xs text-slate-500">{{ day }}</span> · {{ hours }}</div>
                </div>
              </li>
            </ul>
            <NuxtLink to="/get-quote" class="btn-primary w-full mt-5">Request a Quote</NuxtLink>
          </div>
        </aside>
      </div>
    </section>
  </div>
</template>
