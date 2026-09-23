<script setup lang="ts">
definePageMeta({ layout: false })
useSeoMeta({ title: 'Create account | Varni Agro Foods', robots: { index: false } })

const auth = useAuthStore()
const form = reactive({ name: '', email: '', phone: '', company: '', gstin: '', password: '', password_confirmation: '' })
const status = ref<'idle' | 'submitting' | 'error'>('idle')
const errorMessage = ref('')

async function submit() {
  status.value = 'submitting'; errorMessage.value = ''
  try {
    await auth.register(form)
    navigateTo('/portal')
  } catch (e: any) {
    status.value = 'error'
    errorMessage.value = Object.values(e?.data?.errors || {}).flat().join(' ') || e?.data?.message || 'Could not create account.'
  }
}
</script>

<template>
  <div class="min-h-screen grid lg:grid-cols-2">
    <div class="hidden lg:flex bg-gradient-to-br from-brand-700 to-brand-900 text-white p-16 flex-col justify-between">
      <NuxtLink to="/" class="flex items-center gap-2.5 text-white">
        <span class="grid place-items-center w-10 h-10 rounded-xl bg-white/15 font-display font-extrabold">V</span>
        <span class="font-display font-bold text-lg">Varni Agro Foods</span>
      </NuxtLink>
      <div>
        <h2 class="font-display text-4xl font-extrabold">Join the Varni network.</h2>
        <p class="text-brand-100 mt-3 max-w-md">Create your client account to book chambers, track stock and pay invoices online.</p>
      </div>
      <p class="text-xs text-brand-200">© {{ new Date().getFullYear() }} Varni Agro Foods Pvt. Ltd.</p>
    </div>

    <div class="grid place-items-center p-8 sm:p-16 bg-slate-50">
      <form @submit.prevent="submit" class="card p-8 w-full max-w-md space-y-4 bg-white">
        <h1 class="font-display text-2xl font-bold text-slate-900">Create account</h1>
        <div v-if="status === 'error'" class="rounded-xl bg-rose-50 text-rose-800 px-4 py-3 text-sm">{{ errorMessage }}</div>
        <div><label class="field-label">Full name *</label><input v-model="form.name" required class="field-input" /></div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="field-label">Email *</label><input v-model="form.email" type="email" required class="field-input" /></div>
          <div><label class="field-label">Phone *</label><input v-model="form.phone" required class="field-input" /></div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="field-label">Company</label><input v-model="form.company" class="field-input" /></div>
          <div><label class="field-label">GSTIN</label><input v-model="form.gstin" class="field-input" /></div>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div><label class="field-label">Password *</label><input v-model="form.password" type="password" required minlength="8" class="field-input" /></div>
          <div><label class="field-label">Confirm *</label><input v-model="form.password_confirmation" type="password" required class="field-input" /></div>
        </div>
        <button class="btn-primary w-full justify-center" :disabled="status === 'submitting'">
          {{ status === 'submitting' ? 'Creating…' : 'Create account' }}
        </button>
        <p class="text-sm text-slate-600 text-center">
          Already have an account? <NuxtLink to="/login" class="text-brand-700 font-semibold">Sign in</NuxtLink>
        </p>
      </form>
    </div>
  </div>
</template>
