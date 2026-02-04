<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Loading State -->
      <div v-if="productsStore.loading" class="flex justify-center py-12">
        <LoadingSpinner />
      </div>

      <!-- Product Details -->
      <div v-else-if="product" class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="lg:grid lg:grid-cols-2 lg:gap-8">
          <!-- Product Image -->
          <div class="relative bg-gray-200 aspect-square lg:aspect-auto">
            <img 
              v-if="product.image" 
              :src="product.image" 
              :alt="product.name"
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
              <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
          </div>

          <!-- Product Info -->
          <div class="p-8">
            <!-- Breadcrumb -->
            <nav class="mb-4">
              <ol class="flex items-center space-x-2 text-sm text-gray-500">
                <li><router-link to="/" class="hover:text-primary-600">Home</router-link></li>
                <li>/</li>
                <li class="text-gray-900">{{ product.name }}</li>
              </ol>
            </nav>

            <!-- Product Name -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>

            <!-- Price -->
            <div class="mb-6">
              <span class="text-4xl font-bold text-primary-600">{{ formatCurrency(product.price) }}</span>
            </div>

            <!-- Stock Status -->
            <div class="mb-6">
              <div v-if="product.stock > 10" class="flex items-center text-green-600">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">In Stock ({{ product.stock }} available)</span>
              </div>
              <div v-else-if="product.stock > 0" class="flex items-center text-yellow-600">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">Low Stock (Only {{ product.stock }} left)</span>
              </div>
              <div v-else class="flex items-center text-red-600">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">Out of Stock</span>
              </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
              <h2 class="text-lg font-semibold text-gray-900 mb-3">Description</h2>
              <p class="text-gray-600 leading-relaxed">{{ product.description || 'No description available.' }}</p>
            </div>

            <!-- Category -->
            <div v-if="product.category" class="mb-8">
              <span class="inline-block bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-sm font-medium">
                {{ product.category.name }}
              </span>
            </div>

            <!-- Quantity Selector -->
            <div class="mb-8">
              <label class="block text-sm font-medium text-gray-700 mb-3">Quantity</label>
              <div class="flex items-center space-x-4">
                <button 
                  @click="decrementQuantity"
                  class="w-12 h-12 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 transition-colors"
                  :disabled="quantity <= 1"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>
                
                <input
                  v-model.number="quantity"
                  type="number"
                  min="1"
                  :max="product.stock"
                  class="w-20 text-center text-xl font-semibold border-2 border-gray-300 rounded-lg py-2"
                />
                
                <button 
                  @click="incrementQuantity"
                  class="w-12 h-12 flex items-center justify-center bg-gray-200 rounded-full hover:bg-gray-300 transition-colors"
                  :disabled="quantity >= product.stock"
                >
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Add to Cart Button -->
            <div class="flex space-x-4">
              <button 
                @click="handleAddToCart"
                :disabled="product.stock <= 0"
                class="flex-1 btn-primary py-4 text-lg disabled:bg-gray-300"
              >
                <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ product.stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
              </button>
              
              <router-link to="/" class="btn-secondary py-4 px-6">
                Continue Shopping
              </router-link>
            </div>
          </div>
        </div>
      </div>

      <!-- Error State -->
      <div v-else class="text-center py-12">
        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Product not found</h3>
        <router-link to="/" class="text-primary-600 hover:text-primary-700">Back to home</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useProductsStore } from '@/stores/products'
import { useCartStore } from '@/stores/cart'
import { formatCurrency } from '@/utils/helpers'
import LoadingSpinner from '@/components/LoadingSpinner.vue'

const route = useRoute()
const productsStore = useProductsStore()
const cartStore = useCartStore()

const product = ref(null)
const quantity = ref(1)

onMounted(async () => {
  try {
    product.value = await productsStore.fetchProductById(route.params.id)
  } catch (error) {
    console.error('Failed to load product:', error)
  }
})

const incrementQuantity = () => {
  if (quantity.value < product.value.stock) {
    quantity.value++
  }
}

const decrementQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

const handleAddToCart = async () => {
  if (product.value && product.value.stock > 0) {
    await cartStore.addItem(product.value, quantity.value)
    quantity.value = 1
  }
}
</script>
