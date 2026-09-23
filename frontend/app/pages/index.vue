<script setup lang="ts">
import { Snowflake, Wind, Truck, Leaf, Thermometer, ShieldCheck, Activity, FileText, ArrowRight, Star } from 'lucide-vue-next'

definePageMeta({ layout: 'default' })

useSeoMeta({
  title: 'Cold Storage in India | Varni Agro Foods Pvt. Ltd.',
  description: 'FSSAI-certified cold storage, controlled atmosphere, blast freezing & reefer logistics across Gujarat and Maharashtra. 55,000+ MT capacity for potato, fruits, dairy, frozen and pharma.',
  ogTitle: 'Varni Agro Foods — India\'s trusted cold-chain partner',
  ogDescription: 'FSSAI-certified, IoT-monitored cold storage with multi-temperature chambers serving farmers, traders and FMCG brands.',
})

interface ApiCollection<T> { data: T[] }

const { data: facilitiesRes } = await useApi<ApiCollection<any>>('/facilities')
const { data: commoditiesRes } = await useApi<ApiCollection<any>>('/commodities?featured=1')
const { data: servicesRes } = await useApi<ApiCollection<any>>('/services')
const { data: testimonialsRes } = await useApi<any[]>('/testimonials')

const facilities = computed(() => facilitiesRes.value?.data ?? [])
const commodities = computed(() => commoditiesRes.value?.data ?? [])
const services = computed(() => servicesRes.value?.data ?? [])
const testimonials = computed(() => testimonialsRes.value ?? [])

const usps = [
  { icon: ShieldCheck, title: 'FSSAI & HACCP certified', desc: 'Every facility audited annually for hygiene, traceability and food-safety compliance.' },
  { icon: Activity, title: '24×7 IoT monitoring', desc: 'Live temperature, humidity, door & power telemetry with real-time alerts.' },
  { icon: FileText, title: 'GST-ready billing', desc: 'Per-bag, per-MT or seasonal pricing with transparent invoices and online payments.' },
  { icon: Thermometer, title: 'Multi-temperature chambers', desc: 'Cold rooms, freezers, controlled-atmosphere and ripening — under one roof.' },
]

const iconMap: Record<string, any> = {
  snowflake: Snowflake, wind: Wind, leaf: Leaf, truck: Truck, thermometer: Thermometer,
}
function iconFor(name?: string) { return name && iconMap[name] ? iconMap[name] : Snowflake }
</script>

<template>
  <div>
    <!-- HERO -->
    <section class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white">
      <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_30%_20%,white,transparent_50%)]" />
      <div class="container relative grid lg:grid-cols-12 gap-10 py-20 md:py-28">
        <div class="lg:col-span-7 space-y-6">
          <span class="inline-flex items-center gap-2 pill bg-white/10 text-brand-100 backdrop-blur">
            <Snowflake class="w-3.5 h-3.5" /> {{ $t('home.hero.eyebrow') }}
          </span>
          <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-extrabold leading-[1.05] text-white">
            India's trusted <span class="text-brand-300">cold-chain partner</span>
            for fresh food and pharma
          </h1>
          <p class="text-lg text-brand-100/90 max-w-2xl leading-relaxed">
            55,000+ MT of FSSAI-grade cold storage across Gujarat & Maharashtra. From potato to pharma,
            we keep your value cold and your business moving.
          </p>
          <div class="flex flex-wrap gap-3 pt-3">
            <NuxtLink to="/get-quote" class="btn-accent">{{ $t('cta.primary') }} <ArrowRight class="w-4 h-4" /></NuxtLink>
            <NuxtLink to="/facilities" class="btn bg-white/10 text-white hover:bg-white/20">See facilities</NuxtLink>
          </div>
        </div>
        <div class="lg:col-span-5 grid grid-cols-2 gap-4 self-end">
          <div class="card p-5 bg-white/10 backdrop-blur border-white/15">
            <div class="text-3xl font-display font-extrabold text-white">55,000+</div>
            <div class="text-xs uppercase tracking-wide text-brand-200">{{ $t('home.hero.stats.capacity') }}</div>
          </div>
          <div class="card p-5 bg-white/10 backdrop-blur border-white/15">
            <div class="text-3xl font-display font-extrabold text-white">3+</div>
            <div class="text-xs uppercase tracking-wide text-brand-200">{{ $t('home.hero.stats.facilities') }}</div>
          </div>
          <div class="card p-5 bg-white/10 backdrop-blur border-white/15">
            <div class="text-3xl font-display font-extrabold text-white">99.95%</div>
            <div class="text-xs uppercase tracking-wide text-brand-200">{{ $t('home.hero.stats.uptime') }}</div>
          </div>
          <div class="card p-5 bg-white/10 backdrop-blur border-white/15">
            <div class="text-3xl font-display font-extrabold text-white">200+</div>
            <div class="text-xs uppercase tracking-wide text-brand-200">{{ $t('home.hero.stats.clients') }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- USPs -->
    <section class="section">
      <div class="container">
        <div class="text-center max-w-2xl mx-auto mb-12">
          <p class="section-eyebrow">{{ $t('home.usp.title') }}</p>
          <h2 class="section-title">Built for season-long peace of mind.</h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-5">
          <div v-for="(u, i) in usps" :key="i" class="card p-6 hover:shadow-lg transition">
            <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-700 grid place-items-center mb-4">
              <component :is="u.icon" class="w-6 h-6" />
            </div>
            <h3 class="font-semibold text-slate-900 mb-1.5">{{ u.title }}</h3>
            <p class="text-sm text-slate-600 leading-relaxed">{{ u.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- SERVICES -->
    <section class="section bg-slate-50">
      <div class="container">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
          <div>
            <p class="section-eyebrow">{{ $t('home.services.eyebrow') }}</p>
            <h2 class="section-title">{{ $t('home.services.title') }}</h2>
          </div>
          <NuxtLink to="/services" class="btn-outline">{{ $t('home.services.cta') }} <ArrowRight class="w-4 h-4" /></NuxtLink>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
          <NuxtLink v-for="s in services.slice(0, 6)" :key="s.slug" :to="`/services/${s.slug}`"
            class="card p-6 hover:shadow-lg hover:-translate-y-0.5 transition group">
            <div class="w-12 h-12 rounded-xl bg-ice-50 text-ice-600 grid place-items-center mb-4 group-hover:bg-brand-700 group-hover:text-white transition">
              <component :is="iconFor(s.icon)" class="w-6 h-6" />
            </div>
            <h3 class="font-semibold text-slate-900 mb-2">{{ s.name }}</h3>
            <p class="text-sm text-slate-600 leading-relaxed">{{ s.short_description }}</p>
            <p class="text-sm font-semibold text-brand-700 mt-4 inline-flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
              {{ $t('cta.learnMore') }} <ArrowRight class="w-3.5 h-3.5" />
            </p>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- FACILITIES -->
    <section class="section">
      <div class="container">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
          <div>
            <p class="section-eyebrow">{{ $t('home.facilities.eyebrow') }}</p>
            <h2 class="section-title">{{ $t('home.facilities.title') }}</h2>
          </div>
          <NuxtLink to="/facilities" class="btn-outline">{{ $t('home.facilities.cta') }} <ArrowRight class="w-4 h-4" /></NuxtLink>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
          <NuxtLink v-for="f in facilities" :key="f.id" :to="`/facilities/${f.slug}`"
            class="card p-6 hover:shadow-lg hover:-translate-y-0.5 transition group">
            <div class="flex items-start justify-between mb-3">
              <span class="pill bg-brand-50 text-brand-700">{{ f.address.city }}</span>
              <span class="text-xs text-slate-500">{{ f.code }}</span>
            </div>
            <h3 class="font-display text-xl font-bold text-slate-900 mb-1">{{ f.name }}</h3>
            <p class="text-sm text-slate-500 mb-4">{{ f.tagline }}</p>
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
              <div>
                <div class="text-xl font-display font-extrabold text-brand-700">{{ Math.round(f.capacity_mt).toLocaleString() }} MT</div>
                <div class="text-[11px] uppercase tracking-wide text-slate-500">capacity</div>
              </div>
              <span class="text-sm font-semibold text-brand-700 group-hover:translate-x-1 transition flex items-center gap-1.5">
                Explore <ArrowRight class="w-3.5 h-3.5" />
              </span>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- COMMODITIES -->
    <section class="section bg-slate-50">
      <div class="container">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-10">
          <div>
            <p class="section-eyebrow">{{ $t('home.commodities.eyebrow') }}</p>
            <h2 class="section-title">{{ $t('home.commodities.title') }}</h2>
          </div>
          <NuxtLink to="/products" class="btn-outline">{{ $t('home.commodities.cta') }} <ArrowRight class="w-4 h-4" /></NuxtLink>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <NuxtLink v-for="c in commodities" :key="c.id" :to="`/products/${c.slug}`"
            class="card p-5 text-center hover:shadow-lg hover:-translate-y-0.5 transition">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 text-brand-700 grid place-items-center mb-3 text-2xl">🥔</div>
            <h3 class="font-semibold text-slate-900">{{ c.name }}</h3>
            <p class="text-[11px] text-slate-500 mt-1">{{ c.recommended_temp_c.min }}°C – {{ c.recommended_temp_c.max }}°C</p>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- TESTIMONIALS -->
    <section v-if="testimonials.length" class="section">
      <div class="container">
        <div class="text-center max-w-2xl mx-auto mb-12">
          <p class="section-eyebrow">{{ $t('home.testimonials.eyebrow') }}</p>
          <h2 class="section-title">{{ $t('home.testimonials.title') }}</h2>
        </div>
        <div class="grid md:grid-cols-3 gap-5">
          <figure v-for="t in testimonials" :key="t.id" class="card p-6">
            <div class="flex gap-0.5 text-amber-400 mb-3">
              <Star v-for="i in (t.rating || 5)" :key="i" class="w-4 h-4 fill-current" />
            </div>
            <blockquote class="text-slate-700 leading-relaxed">"{{ t.quote }}"</blockquote>
            <figcaption class="mt-4 pt-4 border-t border-slate-100">
              <div class="font-semibold text-slate-900">{{ t.author }}</div>
              <div class="text-xs text-slate-500">{{ t.role }}{{ t.company ? `, ${t.company}` : '' }}</div>
            </figcaption>
          </figure>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="section bg-gradient-to-br from-brand-700 to-brand-800 text-white">
      <div class="container text-center max-w-3xl">
        <h2 class="font-display text-3xl md:text-5xl font-extrabold text-white mb-4">{{ $t('home.cta.title') }}</h2>
        <p class="text-brand-100 text-lg mb-8">{{ $t('home.cta.subtitle') }}</p>
        <NuxtLink to="/get-quote" class="btn-accent">{{ $t('home.cta.button') }}</NuxtLink>
      </div>
    </section>
  </div>
</template>
