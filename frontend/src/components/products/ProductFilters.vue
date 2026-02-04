<script setup>
import { computed } from 'vue'
import { useProductsStore } from '@/stores/products'

const productsStore = useProductsStore()

const searchQuery = computed({
  get: () => productsStore.filters.search,
  set: (value) => productsStore.setFilter('search', value)
})

const selectedCategory = computed({
  get: () => productsStore.filters.category_id,
  set: (value) => productsStore.setFilter('category_id', value)
})

const sortBy = computed({
  get: () => productsStore.filters.sort,
  set: (value) => productsStore.setFilter('sort', value)
})

const minPrice = computed({
  get: () => productsStore.filters.min_price,
  set: (value) => productsStore.setFilter('min_price', value ? parseFloat(value) : null)
})

const maxPrice = computed({
  get: () => productsStore.filters.max_price,
  set: (value) => productsStore.setFilter('max_price', value ? parseFloat(value) : null)
})

const clearFilters = () => {
  productsStore.resetFilters()
}
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Filters</h3>
    
    <!-- Search -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
      <input 
        v-model="searchQuery"
        type="text" 
        placeholder="Search products..."
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
      >
    </div>

    <!-- Category -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
      <select 
        v-model="selectedCategory"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
      >
        <option :value="null">All Categories</option>
        <option 
          v-for="category in productsStore.categories" 
          :key="category.id" 
          :value="category.id"
        >
          {{ category.name }}
        </option>
      </select>
    </div>

    <!-- Price Range -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
      <div class="grid grid-cols-2 gap-2">
        <input 
          v-model="minPrice"
          type="number" 
          placeholder="Min"
          class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
        >
        <input 
          v-model="maxPrice"
          type="number" 
          placeholder="Max"
          class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
        >
      </div>
    </div>

    <!-- Sort -->
    <div class="mb-4">
      <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
      <select 
        v-model="sortBy"
        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
      >
        <option value="name">Name</option>
        <option value="price_asc">Price: Low to High</option>
        <option value="price_desc">Price: High to Low</option>
      </select>
    </div>

    <!-- Clear Filters -->
    <button 
      @click="clearFilters"
      class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300"
    >
      Clear Filters
    </button>
  </div>
</template>
