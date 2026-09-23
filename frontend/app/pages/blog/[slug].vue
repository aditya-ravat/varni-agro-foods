<script setup lang="ts">
const route = useRoute()
const { data: post } = await useApi<any>(`/posts/${route.params.slug}`, {
  transform: (res: any) => res?.data ?? res,
})
if (!post.value) throw createError({ statusCode: 404, statusMessage: 'Post not found' })

useSeoMeta({
  title: post.value.seo?.title || post.value.title,
  description: post.value.seo?.description || post.value.excerpt,
})
</script>

<template>
  <article v-if="post" class="section">
    <div class="container max-w-3xl">
      <NuxtLink to="/blog" class="text-brand-700 text-sm font-semibold">← Back to blog</NuxtLink>
      <h1 class="font-display text-3xl md:text-5xl font-extrabold text-slate-900 mt-4">{{ post.title }}</h1>
      <p class="text-slate-500 mt-3 text-sm">{{ post.author?.name }} · {{ new Date(post.published_at || Date.now()).toLocaleDateString() }}</p>
      <div v-html="post.body_html" class="prose prose-slate max-w-none mt-8" />
    </div>
  </article>
</template>
