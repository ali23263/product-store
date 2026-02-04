<script setup>
import { ref } from 'vue'
import { useCartStore } from '@/stores/cart'

const props = defineProps({
  item: {
    type: Object,
    required: true
  }
})

const cartStore = useCartStore()
const quantity = ref(props.item.quantity)

const updateQuantity = () => {
  cartStore.updateQuantity(props.item.product_id, quantity.value)
}

const removeItem = () => {
  cartStore.removeItem(props.item.product_id)
}
</script>

<template>
  <div class="flex items-center gap-4 p-4 bg-white rounded-lg shadow">
    <img 
      :src="item.image || 'https://via.placeholder.com/100'" 
      :alt="item.name"
      class="w-20 h-20 object-cover rounded"
    >
    
    <div class="flex-grow">
      <h3 class="font-semibold text-gray-900">{{ item.name }}</h3>
      <p class="text-primary-600 font-bold">${{ parseFloat(item.price).toFixed(2) }}</p>
    </div>
    
    <div class="flex items-center gap-2">
      <button 
        @click="quantity--; updateQuantity()"
        :disabled="quantity <= 1"
        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 disabled:opacity-50"
      >
        -
      </button>
      
      <input 
        v-model.number="quantity"
        @change="updateQuantity"
        type="number" 
        min="1"
        class="w-16 text-center border border-gray-300 rounded px-2 py-1"
      >
      
      <button 
        @click="quantity++; updateQuantity()"
        class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300"
      >
        +
      </button>
    </div>
    
    <div class="font-bold text-gray-900">
      ${{ (parseFloat(item.price) * quantity).toFixed(2) }}
    </div>
    
    <button 
      @click="removeItem"
      class="text-red-600 hover:text-red-800"
    >
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
      </svg>
    </button>
  </div>
</template>
