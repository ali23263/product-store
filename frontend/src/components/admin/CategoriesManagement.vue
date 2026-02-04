<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Categories</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h3 class="font-semibold mb-4">Add Category</h3>
        <form @submit.prevent="createCategory" class="space-y-3">
          <input v-model="newCategory.name" type="text" placeholder="Category name" required class="input-field" />
          <button type="submit" class="w-full btn-primary">Add Category</button>
        </form>
      </div>
      
      <div>
        <h3 class="font-semibold mb-4">Existing Categories</h3>
        <div class="space-y-2">
          <div v-for="category in productsStore.categories" :key="category.id" 
               class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
            <span>{{ category.name }}</span>
            <button @click="deleteCategory(category.id)" class="text-red-600 hover:text-red-800">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProductsStore } from '@/stores/products'
import { categoriesAPI } from '@/services/api'
import { useToast } from '@/utils/toast'

const productsStore = useProductsStore()
const { showToast } = useToast()
const newCategory = ref({ name: '' })

onMounted(() => {
  productsStore.fetchCategories()
})

const createCategory = async () => {
  try {
    await categoriesAPI.create(newCategory.value)
    newCategory.value.name = ''
    await productsStore.fetchCategories()
    showToast('Category created', 'success')
  } catch (error) {
    showToast('Failed to create category', 'error')
  }
}

const deleteCategory = async (id) => {
  if (confirm('Delete this category?')) {
    try {
      await categoriesAPI.delete(id)
      await productsStore.fetchCategories()
      showToast('Category deleted', 'success')
    } catch (error) {
      showToast('Failed to delete category', 'error')
    }
  }
}
</script>
