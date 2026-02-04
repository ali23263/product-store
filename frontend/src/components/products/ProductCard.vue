<script setup>
import { RouterLink } from 'vue-router'
import { useCartStore } from '@/stores/cart'

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})

const cartStore = useCartStore()

const addToCart = () => {
  cartStore.addItem(props.product)
}
</script>

<template>
  <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
    <RouterLink :to="`/products/${product.id}`">
      <img 
        :src="product.image || 'https://via.placeholder.com/300'" 
        :alt="product.name" 
        class="w-full h-48 object-cover rounded-t-lg"
      >
    </RouterLink>
    
    <div class="p-4">
      <RouterLink :to="`/products/${product.id}`" class="block">
        <h3 class="text-lg font-semibold text-gray-900 hover:text-primary-600">
          {{ product.name }}
        </h3>
      </RouterLink>
      
      <p class="text-sm text-gray-600 mt-1 line-clamp-2">
        {{ product.description }}
      </p>
      
      <div class="mt-3 flex items-center justify-between">
        <span class="text-xl font-bold text-primary-600">
          ${{ parseFloat(product.price).toFixed(2) }}
        </span>
        
        <button 
          @click="addToCart"
          class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition text-sm"
        >
          Add to Cart
        </button>
      </div>
      
      <div v-if="product.stock !== undefined" class="mt-2 text-sm">
        <span 
          :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'"
        >
          {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
        </span>
      </div>
    </div>
  </div>
</template>
