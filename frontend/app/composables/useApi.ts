import type { UseFetchOptions } from 'nuxt/app'

export function useApi<T>(
  url: string | (() => string),
  options: UseFetchOptions<T> = {},
) {
  const config = useRuntimeConfig()
  const token = useState<string | null>('auth.token', () => null)

  return useFetch(url, {
    baseURL: config.public.apiBase as string,
    onRequest({ options }) {
      options.headers = new Headers(options.headers)
      if (token.value) options.headers.set('Authorization', `Bearer ${token.value}`)
      options.headers.set('Accept', 'application/json')
    },
    ...options,
  })
}

export function $api<T = unknown>(url: string, options: any = {}): Promise<T> {
  const config = useRuntimeConfig()
  const token = useState<string | null>('auth.token')
  const headers = new Headers(options.headers || {})
  if (token.value) headers.set('Authorization', `Bearer ${token.value}`)
  headers.set('Accept', 'application/json')
  return $fetch<T>(url, {
    baseURL: config.public.apiBase as string,
    ...options,
    headers,
  })
}
