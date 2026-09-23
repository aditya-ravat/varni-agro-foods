<script setup lang="ts">
useSeoMeta({
  title: 'Sign in | Varni Agro Foods',
  description: 'Sign in to your Varni Agro Foods client portal or admin console.',
  robots: { index: false, follow: false },
})

definePageMeta({ layout: false })

const auth = useAuthStore()
const route = useRoute()

const form = reactive({ email: '', password: '' })
const status = ref<'idle' | 'submitting' | 'error'>('idle')
const errorMessage = ref('')

async function submit() {
  status.value = 'submitting'
  errorMessage.value = ''
  try {
    const user = await auth.login(form.email, form.password)
    const next = route.query.next as string
    if (next) return navigateTo(next)
    navigateTo(auth.isAdmin ? '/admin' : '/portal')
  } catch (e: any) {
    status.value = 'error'
    errorMessage.value = e?.data?.errors?.email?.[0] || e?.data?.message || 'Invalid credentials.'
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
        <h2 class="font-display text-4xl font-extrabold">Welcome back.</h2>
        <p class="text-brand-100 mt-3 max-w-md">Sign in to manage your cold storage with the Varni client portal.</p>
      </div>
      <p class="text-xs text-brand-200">© {{ new Date().getFullYear() }} Varni Agro Foods Pvt. Ltd.</p>
    </div>
    <div class="grid place-items-center p-8 sm:p-16 bg-slate-50">
      <form @submit.prevent="submit" class="card p-8 w-full max-w-md space-y-5 bg-white">
        <NuxtLink to="/" class="lg:hidden flex items-center gap-2.5 text-brand-700 mb-2">
          <span class="grid place-items-center w-9 h-9 rounded-xl bg-brand-700 text-white font-display font-extrabold">V</span>
          <span class="font-display font-bold">Varni Agro Foods</span>
        </NuxtLink>
        <div>
          <h1 class="font-display text-2xl font-bold text-slate-900">Sign in</h1>
          <p class="text-sm text-slate-600 mt-1">Use your Varni account credentials.</p>
        </div>
        <div v-if="status === 'error'" class="rounded-xl bg-rose-50 text-rose-800 px-4 py-3 text-sm">{{ errorMessage }}</div>
        <div>
          <label class="field-label">Email</label>
          <input v-model="form.email" type="email" required class="field-input" autofocus />
        </div>
        <div>
          <label class="field-label">Password</label>
          <input v-model="form.password" type="password" required class="field-input" />
        </div>
        <button class="btn-primary w-full justify-center" :disabled="status === 'submitting'">
          {{ status === 'submitting' ? 'Signing in…' : 'Sign in' }}
        </button>
        <p class="text-sm text-slate-600 text-center">
          New here? <NuxtLink to="/register" class="text-brand-700 font-semibold">Create an account</NuxtLink>
        </p>
      </form>
    </div>
  </div>
</template>
