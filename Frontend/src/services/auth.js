import { ref, computed } from 'vue'

export const authToken = ref(localStorage.getItem('token') || '')
export const authUser = ref(null)

try {
  authUser.value = JSON.parse(localStorage.getItem('user') || 'null')
} catch {
  authUser.value = null
}

export const isLoggedIn = computed(() => !!authToken.value)
export const isAdmin = computed(() => authUser.value?.role === 'admin')

export function setAuth(token, user) {
  localStorage.setItem('token', token)
  localStorage.setItem('user', JSON.stringify(user))
  authToken.value = token
  authUser.value = user
}

export function clearAuth() {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  authToken.value = ''
  authUser.value = null
}