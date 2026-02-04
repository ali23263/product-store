<template>
  <div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b flex justify-between items-center">
      <h2 class="text-xl font-bold text-gray-900">Products Management</h2>
      <button @click="showCreateModal = true" class="btn-primary">
        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Product
      </button>
    </div>

    <div class="p-6">
      <div v-if="productsStore.loading" class="flex justify-center py-8">
        <LoadingSpinner />
      </div>
      
      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in productsStore.products" :key="product.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ product.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ formatCurrency(product.price) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ product.stock }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ product.category?.name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <button @click="editProduct(product)" class="text-primary-600 hover:text-primary-900">Edit</button>
                <button @click="deleteProductConfirm(product)" class="text-red-600 hover:text-red-900">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <Modal v-model="showCreateModal" :title="editingProduct ? 'Edit Product' : 'Create Product'">
      <form @submit.prevent="saveProduct" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
          <input v-model="form.name" type="text" required class="input-field" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
          <textarea v-model="form.description" rows="3" class="input-field"></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Price</label>
            <input v-model.number="form.price" type="number" step="0.01" required class="input-field" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
            <input v-model.number="form.stock" type="number" required class="input-field" />
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
          <select v-model="form.category_id" class="input-field">
            <option :value="null">Select category</option>
            <option v-for="cat in productsStore.categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Image URL</label>
          <input v-model="form.image" type="text" class="input-field" />
        </div>
      </form>
      <template #footer>
        <div class="flex space-x-2">
          <button @click="showCreateModal = false" class="flex-1 btn-secondary">Cancel</button>
          <button @click="saveProduct" class="flex-1 btn-primary">Save</button>
        </div>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import { useProductsStore } from '@/stores/products'
import { formatCurrency } from '@/utils/helpers'
import Modal from '@/components/Modal.vue'
import LoadingSpinner from '@/components/LoadingSpinner.vue'

const productsStore = useProductsStore()
const showCreateModal = ref(false)
const editingProduct = ref(null)

const form = reactive({
  name: '',
  description: '',
  price: 0,
  stock: 0,
  category_id: null,
  image: '',
})

onMounted(() => {
  productsStore.fetchProducts()
  productsStore.fetchCategories()
})

const editProduct = (product) => {
  editingProduct.value = product
  Object.assign(form, product)
  showCreateModal.value = true
}

const saveProduct = async () => {
  try {
    if (editingProduct.value) {
      await productsStore.updateProduct(editingProduct.value.id, form)
    } else {
      await productsStore.createProduct(form)
    }
    showCreateModal.value = false
    resetForm()
  } catch (error) {
    console.error('Failed to save product:', error)
  }
}

const deleteProductConfirm = async (product) => {
  if (confirm(`Delete ${product.name}?`)) {
    await productsStore.deleteProduct(product.id)
  }
}

const resetForm = () => {
  editingProduct.value = null
  Object.assign(form, { name: '', description: '', price: 0, stock: 0, category_id: null, image: '' })
}
</script>
