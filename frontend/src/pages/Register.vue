<template>
  <div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-2xl p-8">
      <!-- Logo/Header -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-500 to-purple-600 rounded-2xl mb-4">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
        <p class="text-gray-600 mt-2">Join us today</p>
      </div>

      <!-- Register Form -->
      <form @submit.prevent="handleRegister" class="space-y-6">
        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Full Name
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="input-field"
            placeholder="John Doe"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email Address
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="input-field"
            placeholder="you@example.com"
          />
          <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            class="input-field"
            placeholder="••••••••"
          />
          <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
          <p class="mt-1 text-xs text-gray-500">At least 8 characters</p>
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Confirm Password
          </label>
          <input
            id="password_confirmation"
            v-model="form.password_confirmation"
            type="password"
            required
            class="input-field"
            placeholder="••••••••"
          />
          <p v-if="errors.password_confirmation" class="mt-1 text-sm text-red-600">{{ errors.password_confirmation }}</p>
        </div>

        <!-- Error Message -->
        <div v-if="errors.general" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
          {{ errors.general }}
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          :disabled="authStore.loading"
          class="w-full btn-primary"
        >
          <span v-if="!authStore.loading">Create Account</span>
          <span v-else class="flex items-center justify-center">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Creating account...
          </span>
        </button>
      </form>

      <!-- Sign In Link -->
      <p class="mt-8 text-center text-sm text-gray-600">
        Already have an account?
        <router-link to="/login" class="font-medium text-primary-600 hover:text-primary-700">
          Sign in
        </router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const errors = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  general: '',
})

const handleRegister = async () => {
  // Clear previous errors
  Object.keys(errors).forEach(key => errors[key] = '')

  // Validate
  if (!form.name) {
    errors.name = 'Name is required'
    return
  }
  if (!form.email) {
    errors.email = 'Email is required'
    return
  }
  if (!form.password) {
    errors.password = 'Password is required'
    return
  }
  if (form.password.length < 8) {
    errors.password = 'Password must be at least 8 characters'
    return
  }
  if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Passwords do not match'
    return
  }

  try {
    await authStore.register(form)
  } catch (error) {
    if (error.response?.data?.errors) {
      Object.assign(errors, error.response.data.errors)
    } else {
      errors.general = error.response?.data?.message || 'Registration failed. Please try again.'
    }
  }
}
</script>
