// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  modules: [
    '@nuxtjs/tailwindcss',
    '@nuxt/image',
    '@nuxtjs/sitemap',
    '@nuxtjs/robots',
    '@nuxtjs/i18n',
    '@pinia/nuxt',
    '@vueuse/nuxt',
    'nuxt-schema-org',
  ],

  css: ['~/assets/css/app.css'],

  app: {
    head: {
      htmlAttrs: { lang: 'en' },
      titleTemplate: (title?: string) =>
        title ? `${title} | Varni Agro Foods` : 'Varni Agro Foods Pvt. Ltd. — Cold Storage in India',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'theme-color', content: '#0d4f3c' },
        { name: 'description', content: 'FSSAI-certified cold storage and warehousing for potato, fruits, dairy, frozen and pharma — across India by Varni Agro Foods Pvt. Ltd.' },
      ],
      link: [
        { rel: 'icon', type: 'image/svg+xml', href: '/favicon.svg' },
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
        { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap' },
      ],
    },
  },

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api/v1',
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000',
      brandName: 'Varni Agro Foods Pvt. Ltd.',
      brandShort: 'Varni Agro Foods',
      contactPhone: '+91 99999 11111',
      contactEmail: 'hello@varniagrofoods.com',
      whatsapp: '+919999911111',
      gstin: '24AAAAA0000A1Z5',
    },
  },

  site: {
    url: process.env.NUXT_PUBLIC_SITE_URL || 'https://varniagrofoods.com',
    name: 'Varni Agro Foods',
    description: 'Cold storage, controlled atmosphere, blast freezing and reefer logistics across India.',
    defaultLocale: 'en',
  },

  i18n: {
    defaultLocale: 'en',
    strategy: 'no_prefix',
    locales: [
      { code: 'en', name: 'English', language: 'en-IN', file: 'en.json' },
      { code: 'hi', name: 'हिन्दी', language: 'hi-IN', file: 'hi.json' },
      { code: 'gu', name: 'ગુજરાતી', language: 'gu-IN', file: 'gu.json' },
    ],
  },

  image: {
    format: ['avif', 'webp', 'jpg'],
    quality: 80,
    densities: [1, 2],
  },

  schemaOrg: {
    identity: {
      type: 'Organization',
      name: 'Varni Agro Foods Pvt. Ltd.',
      url: process.env.NUXT_PUBLIC_SITE_URL || 'https://varniagrofoods.com',
      logo: '/logo.png',
      sameAs: [],
    },
  },

  robots: {
    disallow: ['/admin', '/portal', '/login', '/register'],
  },

  sitemap: {
    sources: [
      '/api/__sitemap__/urls',
    ],
  },

  nitro: {
    compressPublicAssets: true,
  },

  typescript: {
    strict: true,
  },

  vite: {
    server: {
      hmr: {
        overlay: false,
      },
    },
  },
})
