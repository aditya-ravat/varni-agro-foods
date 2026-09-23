<script setup lang="ts">
useSeoMeta({
  title: 'Blog & Cold Chain Insights | Varni Agro Foods',
  description: 'Articles, seasonal advisories and storage tips for farmers, traders and FMCG brands.',
})

const { data: res } = await useApi<{ data: any[] }>('/posts')
const posts = computed(() => res.value?.data ?? [])
</script>

<template>
  <div>
    <section class="bg-gradient-to-br from-brand-700 to-brand-900 text-white py-16">
      <div class="container">
        <h1 class="section-title text-white">Cold chain insights</h1>
        <p class="text-brand-100 mt-4 max-w-2xl">Storage tips, seasonal advisories and stories from our facilities.</p>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div v-if="posts.length === 0" class="card p-10 text-center text-slate-600">
          We're publishing soon. In the meantime, follow us on LinkedIn for updates.
        </div>
        <div v-else class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <NuxtLink v-for="p in posts" :key="p.slug" :to="`/blog/${p.slug}`" class="card overflow-hidden hover:shadow-lg transition">
            <div class="h-44 bg-gradient-to-br from-brand-200 to-brand-500" />
            <div class="p-6">
              <h3 class="font-display text-lg font-bold text-slate-900 line-clamp-2">{{ p.title }}</h3>
              <p class="text-sm text-slate-600 mt-2 line-clamp-3">{{ p.excerpt }}</p>
            </div>
          </NuxtLink>
        </div>
      </div>
    </section>
  </div>
</template>
