<script setup lang="ts">
useSchemaOrg([
  defineOrganization({
    name: 'Varni Agro Foods Pvt. Ltd.',
    logo: '/logo.png',
    sameAs: [],
  }),
  defineWebSite({
    name: 'Varni Agro Foods',
  }),
])
</script>

<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>
