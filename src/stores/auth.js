import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { apiFetch } from '../services/api.js'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const loading = ref(false)

  const isAuthenticated = computed(() => {
    return user.value !== null
  })

  const isAdmin = computed(() => {
    return user.value?.role === 'admin'
  })

  const isClient = computed(() => {
    return user.value?.role === 'client'
  })

  async function register(name, email, password) {
    loading.value = true

    try {
      return await apiFetch('/auth/register.php', {
        method: 'POST',
        body: JSON.stringify({
          name,
          email,
          password
        })
      })
    } finally {
      loading.value = false
    }
  }

  async function login(email, password) {
    loading.value = true

    try {
      const data = await apiFetch('/auth/login.php', {
        method: 'POST',
        body: JSON.stringify({
          email,
          password
        })
      })

      user.value = data.user

      return data
    } finally {
      loading.value = false
    }
  }

  async function fetchCurrentUser() {
    try {
      const data = await apiFetch('/auth/me.php')

      user.value = data.user

      return true
    } catch {
      user.value = null

      return false
    }
  }

  async function logout() {
    await apiFetch('/auth/logout.php', {
      method: 'POST'
    })

    user.value = null
  }

  return {
    user,
    loading,
    isAuthenticated,
    isAdmin,
    isClient,
    register,
    login,
    fetchCurrentUser,
    logout
  }
})