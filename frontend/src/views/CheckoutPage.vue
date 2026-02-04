<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { useOrdersStore } from '@/stores/orders'
import CartSummary from '@/components/cart/CartSummary.vue'
import AlertMessage from '@/components/common/AlertMessage.vue'

const router = useRouter()
const cartStore = useCartStore()
const ordersStore = useOrdersStore()

const form = ref({
  shipping_address: '',
  city: '',
  state: '',
  zip_code: '',
  phone: ''
})

const error = ref('')
const isSubmitting = ref(false)

const handleSubmit = async () => {
  error.value = ''
  isSubmitting.value = true
  
  try {
    const orderData = {
      items: cartStore.items.map(item => ({
        product_id: item.product_id,
        quantity: item.quantity,
        price: item.price
      })),
      total: cartStore.total,
      promo_code: cartStore.promoCode || null,
      shipping_address: form.value.shipping_address,
      city: form.value.city,
      state: form.value.state,
      zip_code: form.value.zip_code,
      phone: form.value.phone
    }
    
    const order = await ordersStore.createOrder(orderData)
    
    // Clear cart after successful order
    cartStore.clearCart()
    
    // Redirect to order detail
    router.push(`/orders/${order.id}`)
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to place order'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Checkout</h1>

    <AlertMessage 
      v-if="error" 
      type="error" 
      :message="error"
      dismissible
      @dismiss="error = ''"
      class="mb-4"
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Shipping Form -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow p-6">
          <h2 class="text-xl font-semibold mb-6">Shipping Information</h2>

          <form @submit.prevent="handleSubmit" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
              <input 
                v-model="form.shipping_address"
                type="text" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
              >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                <input 
                  v-model="form.city"
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                <input 
                  v-model="form.state"
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
                >
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code</label>
                <input 
                  v-model="form.zip_code"
                  type="text" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
                >
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input 
                  v-model="form.phone"
                  type="tel" 
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
                >
              </div>
            </div>

            <button 
              type="submit"
              :disabled="isSubmitting || cartStore.items.length === 0"
              class="w-full bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed mt-6"
            >
              {{ isSubmitting ? 'Placing Order...' : 'Place Order' }}
            </button>
          </form>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="lg:col-span-1">
        <CartSummary />

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow p-6 mt-4">
          <h3 class="font-semibold mb-4">Order Items ({{ cartStore.items.length }})</h3>
          <div class="space-y-2">
            <div 
              v-for="item in cartStore.items" 
              :key="item.product_id"
              class="flex justify-between text-sm"
            >
              <span>{{ item.name }} × {{ item.quantity }}</span>
              <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
