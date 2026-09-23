<script setup lang="ts">
import { LayoutDashboard, Building2, Box, Apple, Receipt, ScrollText, Megaphone, Settings, LogOut, ChevronDown, ThermometerSnowflake } from 'lucide-vue-next'

const auth = useAuthStore()
const route = useRoute()

const menu = [
  { label: 'Dashboard', to: '/admin', icon: LayoutDashboard },
  { label: 'Facilities', to: '/admin/facilities', icon: Building2 },
  { label: 'Chambers', to: '/admin/chambers', icon: ThermometerSnowflake },
  { label: 'Commodities', to: '/admin/commodities', icon: Apple },
  { label: 'Tariffs', to: '/admin/tariffs', icon: Receipt },
  { label: 'Bookings & Lots', to: '/admin/lots', icon: Box },
  { label: 'Invoices', to: '/admin/invoices', icon: ScrollText },
  { label: 'Leads', to: '/admin/leads', icon: Megaphone },
  { label: 'Settings', to: '/admin/settings', icon: Settings },
]

async function logout() { await auth.logout(); navigateTo('/login') }
</script>

<template>
  <div class="min-h-screen bg-slate-50">
    <aside class="fixed inset-y-0 left-0 w-60 bg-brand-900 text-slate-200 flex flex-col">
      <div class="px-5 py-5 border-b border-brand-800 flex items-center gap-2.5">
        <span class="grid place-items-center w-9 h-9 rounded-lg bg-brand-600 text-white font-display font-extrabold">V</span>
        <div>
          <div class="font-display font-bold text-white text-sm">Varni Admin</div>
          <div class="text-[10px] uppercase tracking-wide text-brand-300">Cold Chain ERP</div>
        </div>
      </div>
      <nav class="flex-1 px-2 py-4 space-y-0.5 overflow-y-auto">
        <NuxtLink v-for="m in menu" :key="m.to" :to="m.to"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm hover:bg-brand-800 hover:text-white transition"
          :class="route.path === m.to || route.path.startsWith(m.to + '/') ? 'bg-brand-700 text-white' : ''">
          <component :is="m.icon" class="w-4 h-4 shrink-0" />
          <span>{{ m.label }}</span>
        </NuxtLink>
      </nav>
      <div class="p-3 border-t border-brand-800">
        <div class="px-2 py-2.5 text-sm">
          <div class="font-medium text-white">{{ auth.user?.name }}</div>
          <div class="text-[11px] text-brand-300">{{ auth.user?.roles?.join(', ') }}</div>
        </div>
        <button @click="logout" class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-sm hover:bg-brand-800 text-brand-200">
          <LogOut class="w-4 h-4" /> Sign out
        </button>
      </div>
    </aside>

    <div class="ml-60 min-h-screen flex flex-col">
      <header class="bg-white border-b border-slate-200 h-14 flex items-center justify-between px-6">
        <div class="text-sm text-slate-500">Hello, <span class="text-slate-900 font-medium">{{ auth.user?.name }}</span></div>
      </header>
      <main class="p-6 flex-1">
        <slot />
      </main>
    </div>
  </div>
</template>
