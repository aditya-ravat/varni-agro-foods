import { defineStore } from 'pinia'

interface User {
  id: number
  name: string
  email: string
  phone?: string
  roles: string[]
  permissions: string[]
}

export const useAuthStore = defineStore('auth', () => {
  const token = useState<string | null>('auth.token', () => useCookie<string | null>('varni_token').value || null)
  const user = ref<User | null>(null)

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() =>
    user.value?.roles.some(r => ['super-admin', 'branch-manager', 'operations-supervisor', 'gate-clerk', 'accounts', 'cms-editor', 'maintenance'].includes(r))
  )
  const isClient = computed(() => user.value?.roles.includes('client'))

  function setToken(t: string | null) {
    token.value = t
    const cookie = useCookie<string | null>('varni_token', { maxAge: 60 * 60 * 24 * 30 })
    cookie.value = t
  }

  async function login(email: string, password: string) {
    const r = await $api<{ token: string; user: User }>('/auth/login', { method: 'POST', body: { email, password } })
    setToken(r.token)
    user.value = r.user
    return r.user
  }

  async function register(payload: any) {
    const r = await $api<{ token: string; user: User }>('/auth/register', { method: 'POST', body: payload })
    setToken(r.token)
    user.value = r.user
    return r.user
  }

  async function fetchMe() {
    if (!token.value) return null
    try {
      user.value = await $api<User>('/auth/me')
      return user.value
    } catch {
      setToken(null)
      user.value = null
      return null
    }
  }

  async function logout() {
    if (token.value) {
      try { await $api('/auth/logout', { method: 'POST' }) } catch { /* noop */ }
    }
    setToken(null)
    user.value = null
  }

  function can(perm: string) {
    return user.value?.permissions?.includes(perm) ?? false
  }

  function hasRole(role: string | string[]) {
    if (!user.value) return false
    const roles = Array.isArray(role) ? role : [role]
    return user.value.roles.some(r => roles.includes(r))
  }

  return { token, user, isAuthenticated, isAdmin, isClient, login, register, logout, fetchMe, can, hasRole, setToken }
})
