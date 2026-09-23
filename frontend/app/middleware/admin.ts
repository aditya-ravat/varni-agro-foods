export default defineNuxtRouteMiddleware(async (to) => {
  const auth = useAuthStore()
  if (!auth.token) return navigateTo({ path: '/login', query: { next: to.fullPath } })
  if (!auth.user) await auth.fetchMe()
  if (!auth.isAdmin) {
    return abortNavigation(createError({ statusCode: 403, statusMessage: 'Forbidden' }))
  }
})
