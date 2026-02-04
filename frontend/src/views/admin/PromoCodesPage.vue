<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import AlertMessage from '@/components/common/AlertMessage.vue'

const promoCodes = ref([])
const loading = ref(false)
const error = ref('')
const success = ref('')

const form = ref({
  code: '',
  discount_type: 'percentage',
  discount_value: '',
  min_purchase: '',
  max_uses: '',
  expires_at: ''
})

const showForm = ref(false)

const fetchPromoCodes = async () => {
  loading.value = true
  try {
    const response = await api.get('/admin/promo-codes')
    promoCodes.value = response.data || response
  } catch (err) {
    error.value = 'Failed to fetch promo codes'
  } finally {
    loading.value = false
  }
}

onMounted(fetchPromoCodes)

const generateCode = () => {
  const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
  let code = ''
  for (let i = 0; i < 8; i++) {
    code += chars.charAt(Math.floor(Math.random() * chars.length))
  }
  form.value.code = code
}

const handleSubmit = async () => {
  error.value = ''
  success.value = ''
  
  try {
    await api.post('/admin/promo-codes', form.value)
    success.value = 'Promo code created successfully'
    await fetchPromoCodes()
    resetForm()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to create promo code'
  }
}

const deletePromoCode = async (id) => {
  if (!confirm('Are you sure you want to delete this promo code?')) return
  
  try {
    await api.delete(`/admin/promo-codes/${id}`)
    success.value = 'Promo code deleted successfully'
    await fetchPromoCodes()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to delete promo code'
  }
}

const resetForm = () => {
  form.value = {
    code: '',
    discount_type: 'percentage',
    discount_value: '',
    min_purchase: '',
    max_uses: '',
    expires_at: ''
  }
  showForm.value = false
}
</script>

<template>
  <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold">Promo Codes</h1>
      <button 
        @click="showForm = !showForm; resetForm()"
        class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700"
      >
        {{ showForm ? 'Cancel' : 'Create Promo Code' }}
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

    <AlertMessage 
      v-if="success" 
      type="success" 
      :message="success"
      dismissible
      @dismiss="success = ''"
      class="mb-4"
    />

    <!-- Promo Code Form -->
    <div v-if="showForm" class="bg-white rounded-lg shadow p-6 mb-6">
      <h2 class="text-xl font-semibold mb-4">Create Promo Code</h2>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
          <div class="flex gap-2">
            <input 
              v-model="form.code"
              type="text" 
              required
              placeholder="SUMMER2024"
              class="flex-grow px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
            >
            <button 
              type="button"
              @click="generateCode"
              class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300"
            >
              Generate
            </button>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Type</label>
            <select 
              v-model="form.discount_type"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
            >
              <option value="percentage">Percentage</option>
              <option value="fixed">Fixed Amount</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Discount Value</label>
            <input 
              v-model.number="form.discount_value"
              type="number" 
              step="0.01"
              required
              :placeholder="form.discount_type === 'percentage' ? '10' : '5.00'"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
            >
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Minimum Purchase</label>
            <input 
              v-model.number="form.min_purchase"
              type="number" 
              step="0.01"
              placeholder="0.00"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Max Uses</label>
            <input 
              v-model.number="form.max_uses"
              type="number"
              placeholder="Unlimited"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
            >
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Expires At</label>
          <input 
            v-model="form.expires_at"
            type="datetime-local"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
          >
        </div>

        <button 
          type="submit"
          class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700"
        >
          Create Promo Code
        </button>
      </form>
    </div>

    <!-- Promo Codes List -->
    <LoadingSpinner v-if="loading" />

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="promoCodes.length === 0" class="text-center py-8 text-gray-600">
        No promo codes yet
      </div>

      <table v-else class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Min Purchase</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uses</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expires</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="promo in promoCodes" :key="promo.id">
            <td class="px-6 py-4 font-mono font-bold">{{ promo.code }}</td>
            <td class="px-6 py-4">
              {{ promo.discount_type === 'percentage' ? `${promo.discount_value}%` : `$${promo.discount_value}` }}
            </td>
            <td class="px-6 py-4">${{ parseFloat(promo.min_purchase || 0).toFixed(2) }}</td>
            <td class="px-6 py-4">{{ promo.used_count || 0 }} / {{ promo.max_uses || '∞' }}</td>
            <td class="px-6 py-4">
              {{ promo.expires_at ? new Date(promo.expires_at).toLocaleDateString() : 'Never' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button 
                @click="deletePromoCode(promo.id)"
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
