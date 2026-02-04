import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { authAPI } from '@/services/api'
import router from '@/router'
import { useToast } from '@/utils/toast'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(null)
  const loading = ref(false)
  const { showToast } = useToast()

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isPicker = computed(() => user.value?.role === 'picker')
  const isCustomer = computed(() => user.value?.role === 'customer')

  const initializeAuth = () => {
    const savedToken = localStorage.getItem('auth_token')
    const savedUser = localStorage.getItem('user')
    
    if (savedToken && savedUser) {
      token.value = savedToken
      user.value = JSON.parse(savedUser)
    }
  }

  const login = async (credentials) => {
    try {
      loading.value = true
      const response = await authAPI.login(credentials)
      
      token.value = response.data.token
      user.value = response.data.user
      
      localStorage.setItem('auth_token', token.value)
      localStorage.setItem('user', JSON.stringify(user.value))
      
      showToast('Login successful!', 'success')
      
      // Redirect based on role
      if (user.value.role === 'admin') {
        router.push('/admin')
      } else if (user.value.role === 'picker') {
        router.push('/picker')
      } else {
        router.push('/')
      }
      
      return response.data
    } catch (error) {
      const message = error.response?.data?.message || 'Login failed'
      showToast(message, 'error')
      throw error
    } finally {
      loading.value = false
    }
  }

  const register = async (userData) => {
    try {
      loading.value = true
      const response = await authAPI.register(userData)
      
      token.value = response.data.token
      user.value = response.data.user
      
      localStorage.setItem('auth_token', token.value)
      localStorage.setItem('user', JSON.stringify(user.value))
      
      showToast('Registration successful!', 'success')
      router.push('/')
      
      return response.data
    } catch (error) {
      const message = error.response?.data?.message || 'Registration failed'
      showToast(message, 'error')
      throw error
    } finally {
      loading.value = false
    }
  }

  const logout = async () => {
    try {
      await authAPI.logout()
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
      showToast('Logged out successfully', 'success')
      router.push('/login')
    }
  }

  return {
    user,
    token,
    loading,
    isAuthenticated,
    isAdmin,
    isPicker,
    isCustomer,
    initializeAuth,
    login,
    register,
    logout,
  }
})
