<script setup>
import { onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import { useProductsStore } from '@/stores/products'
import ProductCard from '@/components/products/ProductCard.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

const productsStore = useProductsStore()

onMounted(async () => {
  await productsStore.fetchProducts()
  await productsStore.fetchCategories()
})
</script>

<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Welcome to E-Shop</h1>
        <p class="text-xl mb-8">Discover amazing products at unbeatable prices</p>
        <RouterLink 
          to="/products" 
          class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 inline-block"
        >
          Shop Now
        </RouterLink>
      </div>
    </section>

    <!-- Categories Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <h2 class="text-3xl font-bold mb-8">Shop by Category</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <RouterLink 
          v-for="category in productsStore.categories.slice(0, 8)" 
          :key="category.id"
          :to="`/products?category=${category.id}`"
          class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition"
        >
          <h3 class="font-semibold">{{ category.name }}</h3>
        </RouterLink>
      </div>
    </section>

    <!-- Featured Products Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-gray-50">
      <h2 class="text-3xl font-bold mb-8">Featured Products</h2>
      
      <LoadingSpinner v-if="productsStore.loading" />
      
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <ProductCard 
          v-for="product in productsStore.products.slice(0, 8)" 
          :key="product.id"
          :product="product"
        />
      </div>

      <div class="text-center mt-8">
        <RouterLink 
          to="/products" 
          class="bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 inline-block"
        >
          View All Products
        </RouterLink>
      </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="text-center">
          <div class="bg-primary-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">Quality Products</h3>
          <p class="text-gray-600">We guarantee the highest quality for all our products</p>
        </div>

        <div class="text-center">
          <div class="bg-primary-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">Best Prices</h3>
          <p class="text-gray-600">Competitive pricing and regular discounts</p>
        </div>

        <div class="text-center">
          <div class="bg-primary-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </div>
          <h3 class="text-xl font-semibold mb-2">Fast Shipping</h3>
          <p class="text-gray-600">Quick and reliable delivery to your doorstep</p>
        </div>
      </div>
    </section>
  </div>
</template>
