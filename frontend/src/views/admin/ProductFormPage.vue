<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useProductsStore } from '@/stores/products'
import AlertMessage from '@/components/common/AlertMessage.vue'

const router = useRouter()
const route = useRoute()
const productsStore = useProductsStore()

const isEditMode = ref(false)
const form = ref({
  name: '',
  description: '',
  price: '',
  stock: '',
  category_id: null,
  image: ''
})

const error = ref('')
const isSubmitting = ref(false)

onMounted(async () => {
  await productsStore.fetchCategories()
  
  if (route.params.id) {
    isEditMode.value = true
    await productsStore.fetchProduct(route.params.id)
    
    if (productsStore.currentProduct) {
      form.value = {
        name: productsStore.currentProduct.name,
        description: productsStore.currentProduct.description || '',
        price: productsStore.currentProduct.price,
        stock: productsStore.currentProduct.stock || 0,
        category_id: productsStore.currentProduct.category_id,
        image: productsStore.currentProduct.image || ''
      }
    }
  }
})

const handleSubmit = async () => {
  error.value = ''
  isSubmitting.value = true
  
  try {
    if (isEditMode.value) {
      await productsStore.updateProduct(route.params.id, form.value)
    } else {
      await productsStore.createProduct(form.value)
    }
    
    router.push('/admin/products')
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to save product'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">{{ isEditMode ? 'Edit Product' : 'Add Product' }}</h1>

    <div class="bg-white rounded-lg shadow p-6">
      <AlertMessage 
        v-if="error" 
        type="error" 
        :message="error"
        dismissible
        @dismiss="error = ''"
        class="mb-4"
      />

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
          <input 
            v-model="form.name"
            type="text" 
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
          >
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
          <textarea 
            v-model="form.description"
            rows="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
          ></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
            <input 
              v-model.number="form.price"
              type="number" 
              step="0.01"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
            <input 
              v-model.number="form.stock"
              type="number" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
            >
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
          <select 
            v-model="form.category_id"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
          >
            <option :value="null">Select a category</option>
            <option 
              v-for="category in productsStore.categories" 
              :key="category.id" 
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
          <input 
            v-model="form.image"
            type="url" 
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
          >
        </div>

        <div class="flex gap-4">
          <button 
            type="submit"
            :disabled="isSubmitting"
            class="flex-1 bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 disabled:opacity-50"
          >
            {{ isSubmitting ? 'Saving...' : (isEditMode ? 'Update Product' : 'Add Product') }}
          </button>

          <button 
            type="button"
            @click="router.push('/admin/products')"
            class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
