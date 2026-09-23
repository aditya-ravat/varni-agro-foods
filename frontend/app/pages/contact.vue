<script setup lang="ts">
import { Phone, Mail, MapPin } from 'lucide-vue-next'

useSeoMeta({
  title: 'Contact Us | Varni Agro Foods',
  description: 'Get in touch with Varni Agro Foods. Phone, email, WhatsApp and facility addresses.',
})

const config = useRuntimeConfig()

const form = reactive({ name: '', email: '', phone: '', subject: '', message: '' })
const status = ref<'idle' | 'submitting' | 'success' | 'error'>('idle')
const errorMessage = ref('')

async function submit() {
  status.value = 'submitting'
  errorMessage.value = ''
  try {
    await $api('/contact', { method: 'POST', body: form })
    status.value = 'success'
    Object.assign(form, { name: '', email: '', phone: '', subject: '', message: '' })
  } catch (e: any) {
    status.value = 'error'
    errorMessage.value = e?.data?.message || 'Could not send. Please try again.'
  }
}
</script>

<template>
  <div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container">
        <h1 class="section-title text-white">Let's talk cold chain.</h1>
        <p class="text-brand-100 mt-4 max-w-2xl">Sales · operations · careers — pick the channel that suits you.</p>
      </div>
    </section>

    <section class="section">
      <div class="container grid lg:grid-cols-12 gap-10">
        <div class="lg:col-span-5 space-y-6">
          <div class="card p-6">
            <Phone class="w-6 h-6 text-brand-700 mb-2" />
            <h3 class="font-semibold text-slate-900 mb-1">Call us</h3>
            <a class="text-brand-700 hover:text-brand-800 font-semibold" :href="`tel:${config.public.contactPhone}`">{{ config.public.contactPhone }}</a>
            <p class="text-sm text-slate-500 mt-1">Mon–Sat · 8:00 — 20:00 IST</p>
          </div>
          <div class="card p-6">
            <Mail class="w-6 h-6 text-brand-700 mb-2" />
            <h3 class="font-semibold text-slate-900 mb-1">Email</h3>
            <a class="text-brand-700 hover:text-brand-800 font-semibold" :href="`mailto:${config.public.contactEmail}`">{{ config.public.contactEmail }}</a>
          </div>
          <div class="card p-6">
            <MapPin class="w-6 h-6 text-brand-700 mb-2" />
            <h3 class="font-semibold text-slate-900 mb-1">Registered office</h3>
            <p class="text-sm text-slate-600">Plot No. 14, GIDC Phase-II, Morbi 363641, Gujarat, India</p>
          </div>
        </div>

        <div class="lg:col-span-7">
          <form @submit.prevent="submit" class="card p-8 space-y-5">
            <h2 class="font-display text-2xl font-bold text-slate-900">Send us a message</h2>
            <div v-if="status === 'success'" class="rounded-xl bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">
              Thank you. Our team will respond within one working day.
            </div>
            <div v-if="status === 'error'" class="rounded-xl bg-rose-50 text-rose-800 px-4 py-3 text-sm">{{ errorMessage }}</div>
            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="field-label">{{ $t('form.name') }} *</label>
                <input v-model="form.name" required class="field-input" />
              </div>
              <div>
                <label class="field-label">{{ $t('form.phone') }}</label>
                <input v-model="form.phone" class="field-input" />
              </div>
              <div>
                <label class="field-label">{{ $t('form.email') }}</label>
                <input v-model="form.email" type="email" class="field-input" />
              </div>
              <div>
                <label class="field-label">Subject</label>
                <input v-model="form.subject" class="field-input" />
              </div>
            </div>
            <div>
              <label class="field-label">{{ $t('form.message') }} *</label>
              <textarea v-model="form.message" required rows="5" class="field-input" />
            </div>
            <button class="btn-primary" :disabled="status === 'submitting'">
              {{ status === 'submitting' ? $t('form.submitting') : $t('form.submit') }}
            </button>
          </form>
        </div>
      </div>
    </section>
  </div>
</template>
