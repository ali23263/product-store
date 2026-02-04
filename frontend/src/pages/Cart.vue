<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

      <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Cart Items -->
        <div class="lg:col-span-2">
          <!-- Empty Cart -->
          <div v-if="cartStore.items.length === 0" class="bg-white rounded-lg shadow-lg p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Your cart is empty</h2>
            <p class="text-gray-500 mb-8">Add some products to get started</p>
            <router-link to="/" class="btn-primary">
              Start Shopping
            </router-link>
          </div>

          <!-- Cart Items List -->
          <div v-else class="space-y-4">
            <CartItem 
              v-for="item in cartStore.items" 
              :key="item.id" 
              :item="item"
            />
          </div>
        </div>

        <!-- Order Summary -->
        <div v-if="cartStore.items.length > 0" class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-lg p-6 sticky top-20">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>

            <!-- Summary Details -->
            <div class="space-y-3 mb-6">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal ({{ cartStore.itemCount }} items)</span>
                <span class="font-medium">{{ formatCurrency(cartStore.subtotal) }}</span>
              </div>
              
              <div v-if="ordersStore.appliedPromo" class="flex justify-between text-green-600">
                <span>Discount ({{ ordersStore.appliedPromo.code }})</span>
                <span class="font-medium">-{{ ordersStore.appliedPromo.discount }}%</span>
              </div>
              
              <div class="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span class="font-medium">Free</span>
              </div>

              <div class="border-t pt-3">
                <div class="flex justify-between text-lg font-bold">
                  <span>Total</span>
                  <span class="text-primary-600">{{ formatCurrency(totalWithDiscount) }}</span>
                </div>
              </div>
            </div>

            <!-- Promo Code -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Promo Code</label>
              <div class="flex space-x-2">
                <input
                  v-model="promoCode"
                  type="text"
                  placeholder="Enter code"
                  class="flex-1 input-field"
                  :disabled="!!ordersStore.appliedPromo"
                />
                <button 
                  v-if="!ordersStore.appliedPromo"
                  @click="applyPromo"
                  :disabled="!promoCode || ordersStore.loading"
                  class="btn-secondary whitespace-nowrap"
                >
                  Apply
                </button>
                <button 
                  v-else
                  @click="removePromo"
                  class="btn-secondary whitespace-nowrap bg-red-50 text-red-600 border-red-600 hover:bg-red-100"
                >
                  Remove
                </button>
              </div>
            </div>

            <!-- Checkout Button -->
            <button 
              @click="handleCheckout"
              :disabled="ordersStore.loading"
              class="w-full btn-primary mb-4"
            >
              <span v-if="!ordersStore.loading">Proceed to Checkout</span>
              <span v-else class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
              </span>
            </button>

            <!-- Continue Shopping -->
            <router-link to="/" class="block w-full btn-secondary text-center">
              Continue Shopping
            </router-link>

            <!-- Security Badge -->
            <div class="mt-6 pt-6 border-t text-center text-sm text-gray-500">
              <svg class="w-5 h-5 inline text-green-600 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              Secure Checkout
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { useOrdersStore } from '@/stores/orders'
import { useAuthStore } from '@/stores/auth'
import { formatCurrency } from '@/utils/helpers'
import CartItem from '@/components/CartItem.vue'

const router = useRouter()
const cartStore = useCartStore()
const ordersStore = useOrdersStore()
const authStore = useAuthStore()

const promoCode = ref('')

const totalWithDiscount = computed(() => {
  if (ordersStore.appliedPromo) {
    const discount = cartStore.subtotal * (ordersStore.appliedPromo.discount / 100)
    return cartStore.subtotal - discount
  }
  return cartStore.subtotal
})

const applyPromo = async () => {
  if (!promoCode.value) return
  
  try {
    await ordersStore.applyPromoCode(promoCode.value)
  } catch (error) {
    console.error('Failed to apply promo code:', error)
  }
}

const removePromo = () => {
  ordersStore.removePromoCode()
  promoCode.value = ''
}

const handleCheckout = async () => {
  // Check if user is authenticated
  if (!authStore.isAuthenticated) {
    router.push({ name: 'Login', query: { redirect: '/cart' } })
    return
  }

  try {
    await ordersStore.createOrder({
      // Additional order data can be added here
    })
    router.push('/dashboard')
  } catch (error) {
    console.error('Checkout failed:', error)
  }
}
</script>
