<script setup lang="ts">
const props = defineProps<{ error: any }>()
const handleError = () => clearError({ redirect: '/' })
</script>

<template>
  <div class="min-h-screen grid place-items-center bg-slate-50 px-6">
    <div class="text-center max-w-md">
      <div class="font-display text-7xl font-extrabold text-brand-700">{{ props.error?.statusCode || 500 }}</div>
      <h1 class="font-display text-2xl font-bold text-slate-900 mt-4">{{ props.error?.statusMessage || 'Something went wrong' }}</h1>
      <p class="text-slate-600 mt-2">The page you're looking for can't be found.</p>
      <button class="btn-primary mt-6" @click="handleError">Back to home</button>
    </div>
  </div>
</template>
