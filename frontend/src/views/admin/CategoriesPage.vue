<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import AlertMessage from '@/components/common/AlertMessage.vue'

const categories = ref([])
const loading = ref(false)
const error = ref('')
const showForm = ref(false)

const form = ref({
  name: '',
  description: ''
})

const editingCategory = ref(null)

const fetchCategories = async () => {
  loading.value = true
  try {
    const response = await api.get('/categories')
    categories.value = response.data || response
  } catch (err) {
    error.value = 'Failed to fetch categories'
  } finally {
    loading.value = false
  }
}

onMounted(fetchCategories)

const handleSubmit = async () => {
  error.value = ''
  
  try {
    if (editingCategory.value) {
      await api.put(`/categories/${editingCategory.value.id}`, form.value)
    } else {
      await api.post('/categories', form.value)
    }
    
    await fetchCategories()
    resetForm()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to save category'
  }
}

const editCategory = (category) => {
  editingCategory.value = category
  form.value = {
    name: category.name,
    description: category.description || ''
  }
  showForm.value = true
}

const deleteCategory = async (id) => {
  if (!confirm('Are you sure you want to delete this category?')) return
  
  try {
    await api.delete(`/categories/${id}`)
    await fetchCategories()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to delete category'
  }
}

const resetForm = () => {
  form.value = { name: '', description: '' }
  editingCategory.value = null
  showForm.value = false
}
</script>

<template>
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold">Categories</h1>
      <button 
        @click="showForm = !showForm; resetForm()"
        class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700"
      >
        {{ showForm ? 'Cancel' : 'Add Category' }}
      </button>
    </div>

    <AlertMessage 
      v-if="error" 
      type="error" 
      :message="error"
      dismissible
      @dismiss="error = ''"
      class="mb-4"
    />

    <!-- Category Form -->
    <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">
        {{ editingCategory ? 'Edit Category' : 'Add Category' }}
      </h2>

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
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
          ></textarea>
        </div>

        <button 
          type="submit"
          class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700"
        >
          {{ editingCategory ? 'Update' : 'Create' }}
        </button>
      </form>
    </div>

    <!-- Categories List -->
    <LoadingSpinner v-if="loading" />

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="categories.length === 0" class="text-center py-8 text-gray-600">
        No categories yet
      </div>

      <table v-else class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="category in categories" :key="category.id">
            <td class="px-6 py-4 font-medium">{{ category.name }}</td>
            <td class="px-6 py-4">{{ category.description || 'N/A' }}</td>
            <td class="px-6 py-4 whitespace-nowrap space-x-2">
              <button 
                @click="editCategory(category)"
                class="text-primary-600 hover:text-primary-800"
              >
                Edit
              </button>
              <button 
                @click="deleteCategory(category.id)"
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
