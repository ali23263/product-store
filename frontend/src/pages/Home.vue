<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-purple-600 text-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to Product Store</h1>
          <p class="text-xl mb-8">Discover amazing products at great prices</p>
          
          <!-- Search Bar -->
          <div class="max-w-2xl mx-auto">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search for products..."
                class="w-full px-6 py-4 rounded-full text-gray-900 focus:outline-none focus:ring-4 focus:ring-purple-300"
                @input="handleSearch"
              />
              <svg class="absolute right-6 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="lg:grid lg:grid-cols-4 lg:gap-8">
        <!-- Sidebar Filters -->
        <aside class="hidden lg:block">
          <div class="bg-white rounded-lg shadow p-6 sticky top-20">
            <h3 class="font-bold text-lg mb-4">Filters</h3>
            
            <!-- Categories -->
            <div class="mb-6">
              <h4 class="font-semibold text-gray-700 mb-3">Categories</h4>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    type="radio"
                    :value="null"
                    v-model="selectedCategory"
                    class="w-4 h-4 text-primary-600"
                    @change="handleFilterChange"
                  />
                  <span class="ml-2 text-sm text-gray-700">All Categories</span>
                </label>
                <label 
                  v-for="category in productsStore.categories" 
                  :key="category.id"
                  class="flex items-center"
                >
                  <input
                    type="radio"
                    :value="category.id"
                    v-model="selectedCategory"
                    class="w-4 h-4 text-primary-600"
                    @change="handleFilterChange"
                  />
                  <span class="ml-2 text-sm text-gray-700">{{ category.name }}</span>
                </label>
              </div>
            </div>

            <!-- Clear Filters -->
            <button 
              @click="clearFilters"
              class="w-full btn-secondary text-sm"
            >
              Clear Filters
            </button>
          </div>
        </aside>

        <!-- Products Grid -->
        <div class="lg:col-span-3">
          <!-- Mobile Filter Button -->
          <div class="lg:hidden mb-4">
            <button class="w-full btn-secondary">
              <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              Filters
            </button>
          </div>

          <!-- Loading State -->
          <div v-if="productsStore.loading" class="flex justify-center py-12">
            <LoadingSpinner />
          </div>

          <!-- Products Grid -->
          <div v-else-if="productsStore.filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <ProductCard 
              v-for="product in productsStore.filteredProducts" 
              :key="product.id" 
              :product="product"
            />
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-12">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">No products found</h3>
            <p class="text-gray-500">Try adjusting your filters or search query</p>
          </div>

          <!-- Pagination -->
          <div v-if="productsStore.pagination.lastPage > 1" class="mt-8 flex justify-center">
            <nav class="flex space-x-2">
              <button
                v-for="page in productsStore.pagination.lastPage"
                :key="page"
                @click="changePage(page)"
                :class="[
                  'px-4 py-2 rounded-lg font-medium transition-colors',
                  page === productsStore.pagination.currentPage
                    ? 'bg-primary-600 text-white'
                    : 'bg-white text-gray-700 hover:bg-gray-100'
                ]"
              >
                {{ page }}
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProductsStore } from '@/stores/products'
import ProductCard from '@/components/ProductCard.vue'
import LoadingSpinner from '@/components/LoadingSpinner.vue'

const productsStore = useProductsStore()
const searchQuery = ref('')
const selectedCategory = ref(null)

onMounted(async () => {
  await Promise.all([
    productsStore.fetchProducts(),
    productsStore.fetchCategories(),
  ])
})

const handleSearch = () => {
  productsStore.setFilter('search', searchQuery.value)
}

const handleFilterChange = () => {
  productsStore.setFilter('category', selectedCategory.value)
}

const clearFilters = () => {
  searchQuery.value = ''
  selectedCategory.value = null
  productsStore.clearFilters()
}

const changePage = (page) => {
  productsStore.pagination.currentPage = page
  productsStore.fetchProducts()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>
