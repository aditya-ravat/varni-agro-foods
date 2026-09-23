<script setup lang="ts">
definePageMeta({ layout: 'portal', middleware: ['client'] })
useSeoMeta({ title: 'Client Portal — Varni Agro Foods', robots: { index: false } })

const auth = useAuthStore()
</script>

<template>
  <div class="space-y-6">
    <div>
      <h1 class="font-display text-2xl font-bold text-slate-900">Welcome, {{ auth.user?.name }}.</h1>
      <p class="text-slate-600 text-sm">Your stock, invoices and gate passes — all in one place.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-4">
      <div class="card p-6">
        <div class="text-xs uppercase tracking-wide text-slate-500">Active lots</div>
        <div class="font-display text-3xl font-extrabold text-slate-900 mt-1">—</div>
      </div>
      <div class="card p-6">
        <div class="text-xs uppercase tracking-wide text-slate-500">Outstanding dues</div>
        <div class="font-display text-3xl font-extrabold text-slate-900 mt-1">₹ —</div>
      </div>
      <div class="card p-6">
        <div class="text-xs uppercase tracking-wide text-slate-500">Open requests</div>
        <div class="font-display text-3xl font-extrabold text-slate-900 mt-1">—</div>
      </div>
    </div>
    <div class="card p-6">
      <p class="text-sm text-slate-600">
        Your lot, booking and invoice modules are being prepared. Once your first consignment is recorded by our operations
        team, you will see live data here.
      </p>
    </div>
  </div>
</template>
