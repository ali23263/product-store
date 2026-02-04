import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'
import { useCartStore } from './cart'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(null)
  const loading = ref(false)
  const error = ref(null)

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isPicker = computed(() => user.value?.role === 'picker')
  const isCustomer = computed(() => user.value?.role === 'customer')

  function loadFromStorage() {
    const storedToken = localStorage.getItem('auth_token')
    const storedUser = localStorage.getItem('user')
    
    if (storedToken && storedUser) {
      token.value = storedToken
      user.value = JSON.parse(storedUser)
    }
  }

  async function login(credentials) {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.post('/login', credentials)
      
      token.value = response.token
      user.value = response.user
      
      localStorage.setItem('auth_token', response.token)
      localStorage.setItem('user', JSON.stringify(response.user))
      
      // Sync cart after login
      const cartStore = useCartStore()
      await cartStore.syncCart()
      
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function register(userData) {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.post('/register', userData)
      
      token.value = response.token
      user.value = response.user
      
      localStorage.setItem('auth_token', response.token)
      localStorage.setItem('user', JSON.stringify(response.user))
      
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Registration failed'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await api.post('/logout')
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
      
      // Clear cart
      const cartStore = useCartStore()
      cartStore.clearCart()
    }
  }

  async function fetchUser() {
    try {
      const response = await api.get('/user')
      user.value = response.user
      localStorage.setItem('user', JSON.stringify(response.user))
    } catch (err) {
      console.error('Failed to fetch user:', err)
    }
  }

  // Load from storage on init
  loadFromStorage()

  return {
    user,
    token,
    loading,
    error,
    isAuthenticated,
    isAdmin,
    isPicker,
    isCustomer,
    login,
    register,
    logout,
    fetchUser
  }
})
