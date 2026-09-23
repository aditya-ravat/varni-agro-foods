<script setup lang="ts">
import { Plus } from 'lucide-vue-next'

definePageMeta({ layout: 'admin', middleware: ['admin'] })

useSeoMeta({ title: 'Facilities — Varni Admin', robots: { index: false } })

const { data: res } = await useApi<{ data: any[] }>('/admin/facilities')
const facilities = computed(() => res.value?.data ?? [])
</script>

<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="font-display text-2xl font-bold text-slate-900">Facilities</h1>
        <p class="text-slate-600 text-sm">Manage your physical cold storage premises.</p>
      </div>
      <NuxtLink to="/admin/facilities/new" class="btn-primary"><Plus class="w-4 h-4" /> New facility</NuxtLink>
    </div>

    <div class="card overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-600 text-xs uppercase">
          <tr>
            <th class="text-left px-5 py-3 font-medium">Code</th>
            <th class="text-left px-5 py-3 font-medium">Name</th>
            <th class="text-left px-5 py-3 font-medium">City</th>
            <th class="text-right px-5 py-3 font-medium">Capacity (MT)</th>
            <th class="text-left px-5 py-3 font-medium">Published</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="f in facilities" :key="f.code" class="border-t border-slate-100 hover:bg-slate-50">
            <td class="px-5 py-3 font-mono text-xs">{{ f.code }}</td>
            <td class="px-5 py-3 font-medium text-slate-900">{{ f.name }}</td>
            <td class="px-5 py-3">{{ f.address.city }}</td>
            <td class="px-5 py-3 text-right">{{ Math.round(f.capacity_mt).toLocaleString() }}</td>
            <td class="px-5 py-3">
              <span class="pill" :class="f.is_published ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600'">
                {{ f.is_published ? 'Live' : 'Draft' }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
