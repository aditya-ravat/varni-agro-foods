<script setup lang="ts">
import { MapPin, Phone, Mail } from 'lucide-vue-next'

const config = useRuntimeConfig()
const email = ref('')
const submitting = ref(false)
const message = ref('')

async function subscribe() {
  if (!email.value) return
  submitting.value = true
  try {
    await $api('/subscribe', { method: 'POST', body: { email: email.value, source: 'footer' } })
    message.value = 'Thanks for subscribing!'
    email.value = ''
  } catch {
    message.value = 'Could not subscribe. Try again later.'
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <footer class="bg-brand-900 text-slate-200 mt-24">
    <div class="container py-16 grid lg:grid-cols-12 gap-10">
      <div class="lg:col-span-4 space-y-4">
        <div class="flex items-center gap-2.5">
          <span class="grid place-items-center w-10 h-10 rounded-xl bg-brand-600 text-white font-display font-extrabold">V</span>
          <span class="font-display text-lg font-bold text-white">Varni Agro Foods Pvt. Ltd.</span>
        </div>
        <p class="text-sm leading-relaxed text-slate-400">
          {{ $t('tagline') }} FSSAI-grade cold storage and reefer logistics serving farmers, traders and FMCG brands across India.
        </p>
        <ul class="space-y-2 text-sm">
          <li class="flex items-start gap-2.5"><MapPin class="w-4 h-4 mt-0.5 text-brand-300" /> {{ $t('footer.address') }}</li>
          <li><a class="flex items-center gap-2.5 hover:text-white" :href="`tel:${config.public.contactPhone}`"><Phone class="w-4 h-4 text-brand-300" /> {{ config.public.contactPhone }}</a></li>
          <li><a class="flex items-center gap-2.5 hover:text-white" :href="`mailto:${config.public.contactEmail}`"><Mail class="w-4 h-4 text-brand-300" /> {{ config.public.contactEmail }}</a></li>
        </ul>
      </div>

      <div class="lg:col-span-2">
        <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">{{ $t('footer.company') }}</h3>
        <ul class="space-y-2.5 text-sm">
          <li><NuxtLink to="/about" class="hover:text-white">{{ $t('nav.about') }}</NuxtLink></li>
          <li><NuxtLink to="/careers" class="hover:text-white">{{ $t('nav.careers') }}</NuxtLink></li>
          <li><NuxtLink to="/blog" class="hover:text-white">{{ $t('nav.blog') }}</NuxtLink></li>
          <li><NuxtLink to="/contact" class="hover:text-white">{{ $t('nav.contact') }}</NuxtLink></li>
        </ul>
      </div>

      <div class="lg:col-span-2">
        <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">{{ $t('footer.services') }}</h3>
        <ul class="space-y-2.5 text-sm">
          <li><NuxtLink to="/services/cold-storage" class="hover:text-white">Cold Storage</NuxtLink></li>
          <li><NuxtLink to="/services/controlled-atmosphere" class="hover:text-white">CA Storage</NuxtLink></li>
          <li><NuxtLink to="/services/blast-freezing" class="hover:text-white">Blast Freezing</NuxtLink></li>
          <li><NuxtLink to="/services/ripening-chambers" class="hover:text-white">Ripening</NuxtLink></li>
          <li><NuxtLink to="/services/reefer-transport" class="hover:text-white">Reefer Transport</NuxtLink></li>
        </ul>
      </div>

      <div class="lg:col-span-4">
        <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wide">{{ $t('footer.newsletter') }}</h3>
        <form @submit.prevent="subscribe" class="flex flex-col sm:flex-row gap-2">
          <input
            v-model="email" type="email" required
            :placeholder="$t('form.subscribe')"
            class="flex-1 rounded-xl px-4 py-2.5 bg-brand-800 border border-brand-700 text-white placeholder-slate-400 focus:ring-2 focus:ring-brand-400 focus:outline-none"
          />
          <button class="btn-accent" :disabled="submitting">{{ submitting ? '…' : $t('footer.subscribe') }}</button>
        </form>
        <p v-if="message" class="text-xs text-brand-200 mt-2">{{ message }}</p>
      </div>
    </div>

    <div class="border-t border-brand-800">
      <div class="container py-6 flex flex-col md:flex-row items-center justify-between gap-3 text-xs text-slate-400">
        <span>© {{ new Date().getFullYear() }} Varni Agro Foods Pvt. Ltd. {{ $t('footer.rights') }}</span>
        <div class="flex items-center gap-5">
          <NuxtLink to="/privacy" class="hover:text-white">{{ $t('footer.privacy') }}</NuxtLink>
          <NuxtLink to="/terms" class="hover:text-white">{{ $t('footer.terms') }}</NuxtLink>
          <span>GSTIN: {{ config.public.gstin }}</span>
        </div>
      </div>
    </div>
  </footer>
</template>
