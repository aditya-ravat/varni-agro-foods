<script setup lang="ts">
useSeoMeta({
  title: 'Get a Quote | Varni Agro Foods',
  description: 'Request a cold storage tariff. Tell us your commodity, quantity and timeline — our team responds within 4 working hours.',
  robots: { index: true, follow: true },
})

const route = useRoute()
const { data: facilitiesRes } = await useApi<{ data: any[] }>('/facilities')
const { data: commoditiesRes } = await useApi<{ data: any[] }>('/commodities')
const facilities = computed(() => facilitiesRes.value?.data ?? [])
const commodities = computed(() => commoditiesRes.value?.data ?? [])

const preselectedSlug = route.query.commodity as string | undefined

const form = reactive({
  name: '', phone: '', email: '', company: '', city: '',
  facility_id: null as number | null,
  commodity_id: null as number | null,
  estimated_qty: null as number | null,
  estimated_qty_unit: 'MT',
  required_from: '', message: '',
  utm_source: route.query.utm_source as string || '',
  utm_medium: route.query.utm_medium as string || '',
  utm_campaign: route.query.utm_campaign as string || '',
})

watchEffect(() => {
  if (preselectedSlug && commodities.value.length) {
    const match = commodities.value.find(c => c.slug === preselectedSlug)
    if (match) form.commodity_id = match.id
  }
})

const status = ref<'idle' | 'submitting' | 'success' | 'error'>('idle')
const reference = ref('')
const errorMessage = ref('')

async function submit() {
  status.value = 'submitting'
  errorMessage.value = ''
  try {
    const r = await $api<{ reference: string }>('/quote', { method: 'POST', body: form })
    reference.value = r.reference
    status.value = 'success'
  } catch (e: any) {
    status.value = 'error'
    errorMessage.value = e?.data?.message || Object.values(e?.data?.errors || {}).flat().join(' ') || 'Could not submit. Please try again.'
  }
}
</script>

<template>
  <div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container max-w-2xl">
        <h1 class="section-title text-white">Get a free cold-storage quote</h1>
        <p class="text-brand-100 mt-4">Tell us a few details. Our team responds within 4 working hours.</p>
      </div>
    </section>

    <section class="section">
      <div class="container max-w-3xl">
        <div v-if="status === 'success'" class="card p-10 text-center">
          <div class="w-14 h-14 mx-auto rounded-full bg-emerald-100 text-emerald-700 grid place-items-center text-2xl mb-4">✓</div>
          <h2 class="font-display text-2xl font-bold text-slate-900 mb-2">Quote request received</h2>
          <p class="text-slate-600">We've recorded your request as <strong>{{ reference }}</strong>. Our team will reach out shortly.</p>
          <NuxtLink to="/" class="btn-outline mt-6">Back to home</NuxtLink>
        </div>

        <form v-else @submit.prevent="submit" class="card p-8 space-y-5">
          <div v-if="status === 'error'" class="rounded-xl bg-rose-50 text-rose-800 px-4 py-3 text-sm">{{ errorMessage }}</div>

          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="field-label">{{ $t('form.name') }} *</label>
              <input v-model="form.name" required class="field-input" />
            </div>
            <div>
              <label class="field-label">{{ $t('form.phone') }} *</label>
              <input v-model="form.phone" required class="field-input" />
            </div>
            <div>
              <label class="field-label">{{ $t('form.email') }}</label>
              <input v-model="form.email" type="email" class="field-input" />
            </div>
            <div>
              <label class="field-label">{{ $t('form.company') }}</label>
              <input v-model="form.company" class="field-input" />
            </div>
            <div>
              <label class="field-label">{{ $t('form.city') }}</label>
              <input v-model="form.city" class="field-input" />
            </div>
            <div>
              <label class="field-label">{{ $t('form.facility') }}</label>
              <select v-model="form.facility_id" class="field-input">
                <option :value="null">— Any —</option>
                <option v-for="f in facilities" :key="f.id" :value="f.id">{{ f.name }}</option>
              </select>
            </div>
            <div>
              <label class="field-label">{{ $t('form.commodity') }}</label>
              <select v-model="form.commodity_id" class="field-input">
                <option :value="null">— Select —</option>
                <option v-for="c in commodities" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
              <div>
                <label class="field-label">{{ $t('form.qty') }}</label>
                <input v-model.number="form.estimated_qty" type="number" step="0.01" class="field-input" />
              </div>
              <div>
                <label class="field-label">{{ $t('form.qtyUnit') }}</label>
                <select v-model="form.estimated_qty_unit" class="field-input">
                  <option>MT</option><option>Bags</option><option>Crates</option><option>Cartons</option>
                </select>
              </div>
            </div>
            <div>
              <label class="field-label">{{ $t('form.requiredFrom') }}</label>
              <input v-model="form.required_from" type="date" class="field-input" />
            </div>
          </div>
          <div>
            <label class="field-label">{{ $t('form.message') }}</label>
            <textarea v-model="form.message" rows="4" class="field-input" />
          </div>

          <button class="btn-primary w-full justify-center" :disabled="status === 'submitting'">
            {{ status === 'submitting' ? $t('form.submitting') : $t('cta.primary') }}
          </button>
          <p class="text-xs text-slate-500 text-center">By submitting, you agree to our privacy policy.</p>
        </form>
      </div>
    </section>
  </div>
</template>
