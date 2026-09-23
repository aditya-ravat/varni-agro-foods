import type { Config } from 'tailwindcss'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

export default {
  content: [
    './app/**/*.{vue,js,ts}',
    './components/**/*.{vue,js,ts}',
    './layouts/**/*.{vue,js,ts}',
    './pages/**/*.{vue,js,ts}',
    './plugins/**/*.{js,ts}',
    './app.vue',
    './error.vue',
  ],
  theme: {
    container: {
      center: true,
      padding: { DEFAULT: '1rem', md: '1.5rem', lg: '2rem' },
      screens: { sm: '640px', md: '768px', lg: '1024px', xl: '1200px', '2xl': '1320px' },
    },
    extend: {
      colors: {
        brand: {
          50: '#ebfaf3',
          100: '#cdf2dd',
          200: '#9ce5bd',
          300: '#67d39a',
          400: '#37bb79',
          500: '#1ea25f',
          600: '#13834c',
          700: '#0d4f3c',
          800: '#0b3f31',
          900: '#062a20',
        },
        ice: {
          50: '#f0f9ff',
          100: '#dff2fe',
          200: '#b9e6fe',
          300: '#7cd1fd',
          400: '#36b9fa',
          500: '#0ca5eb',
          600: '#0085c8',
          700: '#0269a2',
          800: '#075985',
          900: '#0c4a6e',
        },
        accent: {
          DEFAULT: '#f59e0b',
          dark: '#b45309',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        display: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
      },
      boxShadow: {
        soft: '0 6px 24px -10px rgba(13, 79, 60, 0.18)',
      },
      borderRadius: {
        '2xl': '1rem',
        '3xl': '1.5rem',
      },
    },
  },
  plugins: [forms, typography],
} satisfies Config
