<script setup>
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useProductsStore } from '@/stores/products'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

const router = useRouter()
const productsStore = useProductsStore()

onMounted(async () => {
  await productsStore.fetchProducts()
  await productsStore.fetchCategories()
})

const editProduct = (id) => {
  router.push(`/admin/products/${id}/edit`)
}

const deleteProduct = async (id) => {
  if (confirm('Are you sure you want to delete this product?')) {
    await productsStore.deleteProduct(id)
  }
}
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold">Products</h1>
      <router-link 
        to="/admin/products/create"
        class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700"
      >
        Add Product
      </router-link>
    </div>

    <LoadingSpinner v-if="productsStore.loading" />

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="productsStore.products.length === 0" class="text-center py-8 text-gray-600">
        No products yet
      </div>

      <table v-else class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="product in productsStore.products" :key="product.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <img 
                :src="product.image || 'https://via.placeholder.com/50'" 
                :alt="product.name"
                class="w-12 h-12 object-cover rounded"
              >
            </td>
            <td class="px-6 py-4">{{ product.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              {{ productsStore.categories.find(c => c.id === product.category_id)?.name || 'N/A' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">${{ parseFloat(product.price).toFixed(2) }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ product.stock || 0 }}</td>
            <td class="px-6 py-4 whitespace-nowrap space-x-2">
              <button 
                @click="editProduct(product.id)"
                class="text-primary-600 hover:text-primary-800"
              >
                Edit
              </button>
              <button 
                @click="deleteProduct(product.id)"
                class="text-red-600 hover:text-red-800"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
