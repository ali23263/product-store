<template>
  <header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <router-link to="/" class="flex items-center space-x-2">
          <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-purple-600 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
          </div>
          <span class="text-2xl font-bold text-gray-900">Product Store</span>
        </router-link>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-8">
          <router-link to="/" class="text-gray-700 hover:text-primary-600 font-medium transition-colors">
            Home
          </router-link>
          <router-link 
            v-if="authStore.isAuthenticated && authStore.isCustomer" 
            to="/dashboard" 
            class="text-gray-700 hover:text-primary-600 font-medium transition-colors"
          >
            Dashboard
          </router-link>
          <router-link 
            v-if="authStore.isAdmin" 
            to="/admin" 
            class="text-gray-700 hover:text-primary-600 font-medium transition-colors"
          >
            Admin Panel
          </router-link>
          <router-link 
            v-if="authStore.isPicker" 
            to="/picker" 
            class="text-gray-700 hover:text-primary-600 font-medium transition-colors"
          >
            Order Picker
          </router-link>
        </nav>

        <!-- Right side actions -->
        <div class="flex items-center space-x-4">
          <!-- Cart -->
          <router-link 
            to="/cart" 
            class="relative p-2 text-gray-700 hover:text-primary-600 transition-colors"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span 
              v-if="cartStore.itemCount > 0" 
              class="absolute -top-1 -right-1 bg-primary-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center"
            >
              {{ cartStore.itemCount }}
            </span>
          </router-link>

          <!-- Auth buttons -->
          <div v-if="!authStore.isAuthenticated" class="flex items-center space-x-2">
            <router-link to="/login" class="px-4 py-2 text-gray-700 hover:text-primary-600 font-medium">
              Login
            </router-link>
            <router-link to="/register" class="btn-primary">
              Sign Up
            </router-link>
          </div>

          <!-- User menu -->
          <div v-else class="flex items-center space-x-3">
            <span class="text-sm text-gray-700 hidden md:block">{{ authStore.user?.name }}</span>
            <button 
              @click="authStore.logout"
              class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary-600 transition-colors"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
import { useCartStore } from '@/stores/cart'

const authStore = useAuthStore()
const cartStore = useCartStore()
</script>
