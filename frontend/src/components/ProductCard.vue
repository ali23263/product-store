<template>
  <div class="card overflow-hidden group cursor-pointer" @click="goToProduct">
    <!-- Image -->
    <div class="relative overflow-hidden bg-gray-200 aspect-square">
      <img 
        v-if="product.image" 
        :src="product.image" 
        :alt="product.name"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
      />
      <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      </div>
      
      <!-- Stock badge -->
      <div 
        v-if="product.stock <= 0" 
        class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold"
      >
        Out of Stock
      </div>
      <div 
        v-else-if="product.stock < 10" 
        class="absolute top-2 right-2 bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-bold"
      >
        Low Stock
      </div>
    </div>

    <!-- Content -->
    <div class="p-4">
      <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
        {{ product.name }}
      </h3>
      
      <p v-if="product.description" class="text-sm text-gray-600 mb-3 line-clamp-2">
        {{ product.description }}
      </p>

      <div class="flex items-center justify-between">
        <span class="text-2xl font-bold text-primary-600">
          {{ formatCurrency(product.price) }}
        </span>
        
        <button 
          @click.stop="addToCart"
          :disabled="product.stock <= 0"
          class="btn-primary py-2 px-4 text-sm disabled:bg-gray-300"
        >
          <svg v-if="product.stock > 0" class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          {{ product.stock > 0 ? 'Add to Cart' : 'Unavailable' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cart'
import { formatCurrency } from '@/utils/helpers'

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
})

const router = useRouter()
const cartStore = useCartStore()

const goToProduct = () => {
  router.push(`/product/${props.product.id}`)
}

const addToCart = async () => {
  if (props.product.stock > 0) {
    await cartStore.addItem(props.product)
  }
}
</script>
