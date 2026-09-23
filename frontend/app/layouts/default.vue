<script setup lang="ts"></script>

<template>
  <div class="min-h-screen flex flex-col">
    <SiteHeader />
    <main class="flex-1">
      <slot />
    </main>
    <SiteFooter />
    <SiteWhatsAppFab />
  </div>
</template>
