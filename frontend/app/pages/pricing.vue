<script setup lang="ts">
useSeoMeta({
  title: 'Pricing & Tariff | Varni Agro Foods',
  description: 'Indicative cold storage tariffs by commodity. Per-bag-per-month, per-MT-per-day, season packages. Request a custom quote for exact pricing.',
})

const tiers = [
  {
    name: 'Per-bag-per-month',
    price: '₹110 — 130',
    desc: 'Best for potato, onion and bagged vegetables across the season.',
    bullets: ['50 kg jute / PP bags', 'Insurance included', 'Free first labour shift', '15-day notice for release'],
  },
  {
    name: 'Per-MT-per-day',
    price: '₹4 — 12',
    desc: 'Flexible storage for fruits, dairy, frozen and pharma.',
    bullets: ['Multi-temperature chambers', 'Daily proration', 'On-demand release', 'Available across all facilities'],
    featured: true,
  },
  {
    name: 'Season package',
    price: 'Custom',
    desc: 'Lock-in seasonal rates with advance reservation.',
    bullets: ['Up to 25% advance', 'Priority booking', 'Volume discounts', 'Dedicated account manager'],
  },
]
</script>

<template>
  <div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container max-w-3xl text-center mx-auto">
        <h1 class="section-title text-white">Transparent cold-storage pricing</h1>
        <p class="text-brand-100 mt-4">Choose the model that fits your business — pay-as-you-store, daily proration, or seasonal lock-in.</p>
      </div>
    </section>

    <section class="section">
      <div class="container grid md:grid-cols-3 gap-6">
        <div v-for="t in tiers" :key="t.name" class="card p-8" :class="t.featured ? 'border-2 border-brand-700 shadow-lg' : ''">
          <div v-if="t.featured" class="pill bg-brand-700 text-white mb-3">Most popular</div>
          <h3 class="font-display text-xl font-bold text-slate-900">{{ t.name }}</h3>
          <p class="text-sm text-slate-600 mt-1.5">{{ t.desc }}</p>
          <div class="font-display text-3xl font-extrabold text-brand-700 mt-6">{{ t.price }}</div>
          <ul class="mt-5 space-y-2.5 text-sm text-slate-700">
            <li v-for="b in t.bullets" :key="b" class="flex gap-2"><span class="text-brand-600">✓</span>{{ b }}</li>
          </ul>
          <NuxtLink to="/get-quote" class="btn-outline w-full mt-6">Request a quote</NuxtLink>
        </div>
      </div>
      <p class="text-center text-xs text-slate-500 mt-8 max-w-2xl mx-auto">
        Indicative rates only. Final tariff depends on commodity, chamber, season and surcharges (handling, fumigation, electricity).
        GST applicable as per HSN of the commodity.
      </p>
    </section>
  </div>
</template>
