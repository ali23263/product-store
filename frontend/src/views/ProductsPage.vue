<script setup>
import { onMounted } from 'vue'
import { useProductsStore } from '@/stores/products'
import ProductCard from '@/components/products/ProductCard.vue'
import ProductFilters from '@/components/products/ProductFilters.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

const productsStore = useProductsStore()

onMounted(async () => {
  await productsStore.fetchProducts()
  await productsStore.fetchCategories()
})
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Products</h1>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <!-- Filters Sidebar -->
      <aside class="lg:col-span-1">
        <ProductFilters />
      </aside>

      <!-- Products Grid -->
      <main class="lg:col-span-3">
        <LoadingSpinner v-if="productsStore.loading" />

        <div v-else-if="productsStore.filteredProducts.length === 0" class="text-center py-16">
          <p class="text-gray-600 text-lg">No products found</p>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <ProductCard 
            v-for="product in productsStore.filteredProducts" 
            :key="product.id"
            :product="product"
          />
        </div>
      </main>
    </div>
  </div>
</template>
