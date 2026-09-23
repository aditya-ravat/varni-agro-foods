<script setup lang="ts">
const route = useRoute()
const { data: service } = await useApi<any>(`/services/${route.params.slug}`, {
  transform: (res: any) => res?.data ?? res,
})

if (!service.value) {
  throw createError({ statusCode: 404, statusMessage: 'Service not found' })
}

const s = computed(() => service.value)

useSeoMeta({
  title: s.value.seo?.title || s.value.name,
  description: s.value.seo?.description || s.value.short_description,
})
</script>

<template>
  <div v-if="s">
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container">
        <NuxtLink to="/services" class="text-brand-200 hover:text-white text-sm">← All services</NuxtLink>
        <h1 class="font-display text-3xl md:text-5xl font-extrabold text-white mt-3">{{ s.name }}</h1>
        <p class="text-brand-100 text-lg mt-3 max-w-2xl">{{ s.short_description }}</p>
      </div>
    </section>
    <section class="section">
      <div class="container grid lg:grid-cols-12 gap-10">
        <article class="lg:col-span-8 prose prose-slate max-w-none" v-html="s.body_html" />
        <aside class="lg:col-span-4">
          <div class="card p-6 sticky top-24">
            <h3 class="font-semibold text-slate-900 mb-3">Get a quote for {{ s.name }}</h3>
            <p class="text-sm text-slate-600 mb-4">Tell us your commodity, quantity and timeline and we'll respond within 4 working hours.</p>
            <NuxtLink to="/get-quote" class="btn-primary w-full">Request a Quote</NuxtLink>
          </div>
        </aside>
      </div>
    </section>
  </div>
</template>
