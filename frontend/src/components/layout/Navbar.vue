<script setup>
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useCartStore } from '@/stores/cart'

const authStore = useAuthStore()
const cartStore = useCartStore()

const handleLogout = async () => {
  await authStore.logout()
  window.location.href = '/'
}
</script>

<template>
  <nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center">
          <RouterLink to="/" class="text-2xl font-bold text-primary-600">
            E-Shop
          </RouterLink>
          
          <div class="hidden md:flex ml-10 space-x-8">
            <RouterLink to="/" class="text-gray-700 hover:text-primary-600">Home</RouterLink>
            <RouterLink to="/products" class="text-gray-700 hover:text-primary-600">Products</RouterLink>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <RouterLink to="/cart" class="relative text-gray-700 hover:text-primary-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span v-if="cartStore.itemCount > 0" class="absolute -top-2 -right-2 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
              {{ cartStore.itemCount }}
            </span>
          </RouterLink>

          <template v-if="authStore.isAuthenticated">
            <RouterLink 
              v-if="authStore.isAdmin" 
              to="/admin/dashboard" 
              class="text-gray-700 hover:text-primary-600"
            >
              Admin
            </RouterLink>
            
            <RouterLink 
              v-if="authStore.isPicker" 
              to="/picker/orders" 
              class="text-gray-700 hover:text-primary-600"
            >
              Picker
            </RouterLink>
            
            <RouterLink 
              v-if="authStore.isCustomer" 
              to="/dashboard" 
              class="text-gray-700 hover:text-primary-600"
            >
              Dashboard
            </RouterLink>

            <button @click="handleLogout" class="text-gray-700 hover:text-primary-600">
              Logout
            </button>
          </template>

          <template v-else>
            <RouterLink to="/login" class="text-gray-700 hover:text-primary-600">Login</RouterLink>
            <RouterLink to="/register" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700">
              Register
            </RouterLink>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>
