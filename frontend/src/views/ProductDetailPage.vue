<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useProductsStore } from '@/stores/products'
import { useCartStore } from '@/stores/cart'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import AlertMessage from '@/components/common/AlertMessage.vue'

const route = useRoute()
const productsStore = useProductsStore()
const cartStore = useCartStore()

const quantity = ref(1)
const addedToCart = ref(false)

onMounted(async () => {
  await productsStore.fetchProduct(route.params.id)
})

const addToCart = () => {
  if (productsStore.currentProduct) {
    cartStore.addItem(productsStore.currentProduct, quantity.value)
    addedToCart.value = true
    setTimeout(() => {
      addedToCart.value = false
    }, 3000)
  }
}
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <LoadingSpinner v-if="productsStore.loading" />

    <div v-else-if="productsStore.currentProduct" class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Product Image -->
      <div>
        <img 
          :src="productsStore.currentProduct.image || 'https://via.placeholder.com/600'" 
          :alt="productsStore.currentProduct.name"
          class="w-full rounded-lg shadow-lg"
        >
      </div>

      <!-- Product Info -->
      <div>
        <h1 class="text-3xl font-bold mb-4">{{ productsStore.currentProduct.name }}</h1>
        
        <div class="text-3xl font-bold text-primary-600 mb-6">
          ${{ parseFloat(productsStore.currentProduct.price).toFixed(2) }}
        </div>

        <div v-if="productsStore.currentProduct.description" class="mb-6">
          <h2 class="text-xl font-semibold mb-2">Description</h2>
          <p class="text-gray-700">{{ productsStore.currentProduct.description }}</p>
        </div>

        <div v-if="productsStore.currentProduct.stock !== undefined" class="mb-6">
          <span 
            :class="[
              'px-3 py-1 rounded-full text-sm font-medium',
              productsStore.currentProduct.stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
            ]"
          >
            {{ productsStore.currentProduct.stock > 0 ? `${productsStore.currentProduct.stock} in stock` : 'Out of stock' }}
          </span>
        </div>

        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
          <div class="flex items-center gap-2">
            <button 
              @click="quantity = Math.max(1, quantity - 1)"
              class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
            >
              -
            </button>
            
            <input 
              v-model.number="quantity"
              type="number" 
              min="1"
              class="w-20 text-center border border-gray-300 rounded px-3 py-2"
            >
            
            <button 
              @click="quantity++"
              class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
            >
              +
            </button>
          </div>
        </div>

        <button 
          @click="addToCart"
          :disabled="productsStore.currentProduct.stock === 0"
          class="w-full bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          Add to Cart
        </button>

        <AlertMessage 
          v-if="addedToCart"
          type="success"
          message="Product added to cart!"
          class="mt-4"
        />
      </div>
    </div>

    <div v-else class="text-center py-16">
      <p class="text-gray-600 text-lg">Product not found</p>
    </div>
  </div>
</template>
