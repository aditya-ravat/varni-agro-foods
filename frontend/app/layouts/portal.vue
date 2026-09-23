<script setup lang="ts">
import { LayoutDashboard, Box, Calendar, FileText, ScrollText, User2, LogOut } from 'lucide-vue-next'

const auth = useAuthStore()
const route = useRoute()

const menu = [
  { label: 'Dashboard', to: '/portal', icon: LayoutDashboard },
  { label: 'My Lots', to: '/portal/lots', icon: Box },
  { label: 'Bookings', to: '/portal/bookings', icon: Calendar },
  { label: 'Gate Passes', to: '/portal/gate-passes', icon: FileText },
  { label: 'Invoices', to: '/portal/invoices', icon: ScrollText },
  { label: 'Profile', to: '/portal/profile', icon: User2 },
]

async function logout() { await auth.logout(); navigateTo('/login') }
</script>

<template>
  <div class="min-h-screen bg-slate-50">
    <SiteHeader />
    <div class="container py-8 grid lg:grid-cols-12 gap-6">
      <aside class="lg:col-span-3 lg:sticky lg:top-24 self-start">
        <nav class="card p-3 space-y-0.5">
          <NuxtLink v-for="m in menu" :key="m.to" :to="m.to"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-brand-50 hover:text-brand-700 transition"
            :class="route.path === m.to ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-slate-700'">
            <component :is="m.icon" class="w-4 h-4" />
            <span>{{ m.label }}</span>
          </NuxtLink>
          <button @click="logout" class="flex items-center gap-3 w-full text-left px-3 py-2.5 rounded-lg text-sm text-rose-600 hover:bg-rose-50">
            <LogOut class="w-4 h-4" /> Sign out
          </button>
        </nav>
      </aside>
      <main class="lg:col-span-9">
        <slot />
      </main>
    </div>
  </div>
</template>
