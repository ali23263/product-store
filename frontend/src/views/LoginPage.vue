<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AlertMessage from '@/components/common/AlertMessage.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: ''
})

const error = ref('')

const handleLogin = async () => {
  error.value = ''
  
  try {
    await authStore.login(form.value)
    
    const redirect = route.query.redirect || '/'
    router.push(redirect)
  } catch (err) {
    error.value = err.response?.data?.message || 'Login failed'
  }
}
</script>

<template>
  <div class="min-h-[80vh] flex items-center justify-center px-4">
    <div class="max-w-md w-full">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-center mb-8">Login</h1>

        <AlertMessage 
          v-if="error" 
          type="error" 
          :message="error"
          dismissible
          @dismiss="error = ''"
          class="mb-4"
        />

        <form @submit.prevent="handleLogin" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input 
              v-model="form.email"
              type="email" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input 
              v-model="form.password"
              type="password" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
            >
          </div>

          <button 
            type="submit"
            :disabled="authStore.loading"
            class="w-full bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 disabled:opacity-50"
          >
            {{ authStore.loading ? 'Logging in...' : 'Login' }}
          </button>
        </form>

        <p class="text-center mt-6 text-gray-600">
          Don't have an account? 
          <router-link to="/register" class="text-primary-600 hover:text-primary-700 font-semibold">
            Register
          </router-link>
        </p>
      </div>
    </div>
  </div>
</template>
