<script setup>
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import CartItem from '@/components/cart/CartItem.vue'
import CartSummary from '@/components/cart/CartSummary.vue'

const router = useRouter()
const cartStore = useCartStore()

const proceedToCheckout = () => {
  router.push('/checkout')
}
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>

    <div v-if="cartStore.items.length === 0" class="text-center py-16">
      <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
      </svg>
      <p class="text-xl text-gray-600 mb-4">Your cart is empty</p>
      <router-link to="/products" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 inline-block">
        Continue Shopping
      </router-link>
    </div>

    <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Cart Items -->
      <div class="lg:col-span-2 space-y-4">
        <CartItem 
          v-for="item in cartStore.items" 
          :key="item.product_id"
          :item="item"
        />
      </div>

      <!-- Cart Summary -->
      <div class="lg:col-span-1">
        <CartSummary>
          <template #action>
            <button 
              @click="proceedToCheckout"
              class="w-full bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700"
            >
              Proceed to Checkout
            </button>
          </template>
        </CartSummary>
      </div>
    </div>
  </div>
</template>
