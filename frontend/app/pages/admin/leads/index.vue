<script setup lang="ts">
definePageMeta({ layout: 'admin', middleware: ['admin'] })
useSeoMeta({ title: 'Leads — Varni Admin', robots: { index: false } })

const { data: res } = await useApi<{ data: any[] }>('/admin/leads')
const leads = computed(() => res.value?.data ?? [])
</script>

<template>
  <div class="space-y-6">
    <h1 class="font-display text-2xl font-bold text-slate-900">Leads</h1>
    <div class="card overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
          <tr>
            <th class="text-left px-5 py-3 font-medium">Name</th>
            <th class="text-left px-5 py-3 font-medium">Phone</th>
            <th class="text-left px-5 py-3 font-medium">Email</th>
            <th class="text-left px-5 py-3 font-medium">City</th>
            <th class="text-left px-5 py-3 font-medium">Commodity</th>
            <th class="text-left px-5 py-3 font-medium">Source</th>
            <th class="text-left px-5 py-3 font-medium">Status</th>
            <th class="text-left px-5 py-3 font-medium">Received</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="!leads.length"><td colspan="8" class="px-5 py-8 text-center text-slate-500">No leads yet.</td></tr>
          <tr v-for="l in leads" :key="l.id" class="border-t border-slate-100 hover:bg-slate-50">
            <td class="px-5 py-3 font-medium text-slate-900">{{ l.name }}</td>
            <td class="px-5 py-3"><a class="text-brand-700" :href="`tel:${l.phone}`">{{ l.phone }}</a></td>
            <td class="px-5 py-3">{{ l.email }}</td>
            <td class="px-5 py-3">{{ l.city }}</td>
            <td class="px-5 py-3">{{ l.commodity?.name }}</td>
            <td class="px-5 py-3 text-xs text-slate-500">{{ l.source }}</td>
            <td class="px-5 py-3"><span class="pill bg-amber-50 text-amber-700">{{ l.status }}</span></td>
            <td class="px-5 py-3 text-xs text-slate-500">{{ new Date(l.created_at).toLocaleDateString() }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
