<template>
  <div id="app" class="min-h-screen flex flex-col">
    <Toast />
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useCartStore } from '@/stores/cart'
import Toast from '@/components/Toast.vue'

const authStore = useAuthStore()
const cartStore = useCartStore()

onMounted(() => {
  // Initialize stores from localStorage
  authStore.initializeAuth()
  cartStore.loadCart()
})
</script>
