<script setup lang="ts">
import { Building2, Box, ScrollText, Megaphone } from 'lucide-vue-next'

definePageMeta({ layout: 'admin', middleware: ['admin'] })

useSeoMeta({ title: 'Dashboard — Varni Admin', robots: { index: false } })

const auth = useAuthStore()

interface ApiCollection<T> { data: T[]; meta?: { total?: number } }
const { data: facilities } = await useApi<ApiCollection<any>>('/admin/facilities')
const { data: leads } = await useApi<ApiCollection<any>>('/admin/leads')

const stats = computed(() => [
  { label: 'Facilities', value: facilities.value?.meta?.total ?? facilities.value?.data?.length ?? 0, icon: Building2, color: 'bg-brand-50 text-brand-700' },
  { label: 'New leads', value: leads.value?.data?.filter((l: any) => l.status === 'new').length ?? 0, icon: Megaphone, color: 'bg-amber-50 text-amber-700' },
  { label: 'Lots in storage', value: '—', icon: Box, color: 'bg-ice-50 text-ice-700' },
  { label: 'Open invoices', value: '—', icon: ScrollText, color: 'bg-rose-50 text-rose-700' },
])
</script>

<template>
  <div class="space-y-6">
    <div>
      <h1 class="font-display text-2xl font-bold text-slate-900">Welcome, {{ auth.user?.name }}.</h1>
      <p class="text-slate-600 mt-1 text-sm">Here's a snapshot of operations across your facilities.</p>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
      <div v-for="s in stats" :key="s.label" class="card p-5">
        <div class="flex items-center gap-3 mb-3">
          <div :class="['w-10 h-10 rounded-xl grid place-items-center', s.color]">
            <component :is="s.icon" class="w-5 h-5" />
          </div>
          <div class="text-xs uppercase tracking-wide text-slate-500">{{ s.label }}</div>
        </div>
        <div class="font-display text-3xl font-extrabold text-slate-900">{{ s.value }}</div>
      </div>
    </div>

    <div class="card p-6">
      <h2 class="font-display text-lg font-bold text-slate-900 mb-4">Recent leads</h2>
      <div v-if="!leads?.data?.length" class="text-sm text-slate-500">No leads yet.</div>
      <table v-else class="w-full text-sm">
        <thead>
          <tr class="text-left text-slate-500 border-b border-slate-100">
            <th class="py-2 font-medium">Name</th>
            <th class="py-2 font-medium">Phone</th>
            <th class="py-2 font-medium">City</th>
            <th class="py-2 font-medium">Commodity</th>
            <th class="py-2 font-medium">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="l in leads.data.slice(0, 8)" :key="l.id" class="border-b border-slate-50 hover:bg-slate-50">
            <td class="py-2 font-medium text-slate-900">{{ l.name }}</td>
            <td class="py-2">{{ l.phone }}</td>
            <td class="py-2">{{ l.city }}</td>
            <td class="py-2">{{ l.commodity?.name }}</td>
            <td class="py-2"><span class="pill bg-amber-50 text-amber-700">{{ l.status }}</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
