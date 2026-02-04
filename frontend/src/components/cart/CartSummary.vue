<script setup>
import { ref } from 'vue'
import { useCartStore } from '@/stores/cart'
import AlertMessage from '@/components/common/AlertMessage.vue'

const cartStore = useCartStore()

const promoCodeInput = ref('')
const promoMessage = ref('')
const promoMessageType = ref('info')

const applyPromo = async () => {
  if (!promoCodeInput.value) return
  
  const result = await cartStore.applyPromoCode(promoCodeInput.value)
  promoMessage.value = result.message
  promoMessageType.value = result.success ? 'success' : 'error'
  
  if (result.success) {
    promoCodeInput.value = ''
  }
}

const clearPromo = () => {
  cartStore.clearPromoCode()
  promoMessage.value = ''
}
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
    
    <!-- Promo Code -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Promo Code</label>
      <div class="flex gap-2">
        <input 
          v-model="promoCodeInput"
          type="text" 
          placeholder="Enter promo code"
          class="flex-grow px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
        >
        <button 
          @click="applyPromo"
          class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700"
        >
          Apply
        </button>
      </div>
      
      <AlertMessage 
        v-if="promoMessage" 
        :type="promoMessageType" 
        :message="promoMessage"
        dismissible
        @dismiss="promoMessage = ''"
        class="mt-2"
      />
    </div>

    <!-- Applied Promo -->
    <div v-if="cartStore.promoCode" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg flex justify-between items-center">
      <span class="text-green-800 font-medium">{{ cartStore.promoCode }} applied</span>
      <button @click="clearPromo" class="text-red-600 hover:text-red-800">Remove</button>
    </div>

    <!-- Totals -->
    <div class="space-y-2 border-t pt-4">
      <div class="flex justify-between text-gray-700">
        <span>Subtotal</span>
        <span>${{ cartStore.subtotal.toFixed(2) }}</span>
      </div>
      
      <div v-if="cartStore.discount > 0" class="flex justify-between text-green-600">
        <span>Discount</span>
        <span>-${{ cartStore.discount.toFixed(2) }}</span>
      </div>
      
      <div class="flex justify-between text-xl font-bold text-gray-900 border-t pt-2">
        <span>Total</span>
        <span>${{ cartStore.total.toFixed(2) }}</span>
      </div>
    </div>

    <!-- Action Slot -->
    <div class="mt-6">
      <slot name="action"></slot>
    </div>
  </div>
</template>
