<template>
  <div class="flex items-center space-x-4 bg-white p-4 rounded-lg shadow">
    <!-- Image -->
    <img 
      v-if="item.image" 
      :src="item.image" 
      :alt="item.name"
      class="w-20 h-20 object-cover rounded"
    />
    <div v-else class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center">
      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
    </div>

    <!-- Details -->
    <div class="flex-1">
      <h3 class="font-semibold text-gray-900">{{ item.name }}</h3>
      <p class="text-sm text-gray-600">{{ formatCurrency(item.price) }} each</p>
    </div>

    <!-- Quantity controls -->
    <div class="flex items-center space-x-2">
      <button 
        @click="cartStore.decrementQuantity(item.id)"
        class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 transition-colors"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
        </svg>
      </button>
      
      <span class="w-12 text-center font-semibold">{{ item.quantity }}</span>
      
      <button 
        @click="cartStore.incrementQuantity(item.id)"
        class="w-8 h-8 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 transition-colors"
        :disabled="item.quantity >= item.stock"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
      </button>
    </div>

    <!-- Subtotal -->
    <div class="text-right">
      <p class="font-bold text-gray-900">{{ formatCurrency(item.price * item.quantity) }}</p>
    </div>

    <!-- Remove button -->
    <button 
      @click="cartStore.removeItem(item.id)"
      class="text-red-500 hover:text-red-700 transition-colors"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
      </svg>
    </button>
  </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cart'
import { formatCurrency } from '@/utils/helpers'

defineProps({
  item: {
    type: Object,
    required: true,
  },
})

const cartStore = useCartStore()
</script>
