<template>
  <div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
      <h2 class="text-xl font-bold text-gray-900">Promo Codes Management</h2>
    </div>

    <div class="p-6">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Create Promo Code -->
        <div class="bg-gray-50 rounded-lg p-6">
          <h3 class="text-lg font-semibold mb-4">Create New Promo Code</h3>
          <form @submit.prevent="createPromoCode" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
              <div class="flex space-x-2">
                <input v-model="form.code" type="text" required class="flex-1 input-field" placeholder="SUMMER2024" />
                <button type="button" @click="generateCode" class="btn-secondary whitespace-nowrap">
                  Generate
                </button>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Discount (%)</label>
              <input v-model.number="form.discount" type="number" min="1" max="100" required class="input-field" />
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Valid From</label>
                <input v-model="form.valid_from" type="date" class="input-field" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Valid Until</label>
                <input v-model="form.valid_until" type="date" class="input-field" />
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Usage Limit</label>
              <input v-model.number="form.usage_limit" type="number" min="1" class="input-field" placeholder="Leave empty for unlimited" />
            </div>
            
            <button type="submit" :disabled="loading" class="w-full btn-primary">
              Create Promo Code
            </button>
          </form>
        </div>

        <!-- Existing Promo Codes -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Existing Promo Codes</h3>
          
          <div v-if="loading" class="flex justify-center py-8">
            <LoadingSpinner />
          </div>
          
          <div v-else-if="promoCodes.length > 0" class="space-y-3">
            <div 
              v-for="promo in promoCodes" 
              :key="promo.id"
              class="bg-gray-50 rounded-lg p-4"
            >
              <div class="flex items-start justify-between mb-2">
                <div>
                  <h4 class="font-bold text-lg text-primary-600">{{ promo.code }}</h4>
                  <p class="text-sm text-gray-600">{{ promo.discount }}% discount</p>
                </div>
                <button @click="deletePromo(promo.id)" class="text-red-600 hover:text-red-800">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
              
              <div class="text-xs text-gray-500 space-y-1">
                <p v-if="promo.valid_from">Valid from: {{ formatDate(promo.valid_from) }}</p>
                <p v-if="promo.valid_until">Valid until: {{ formatDate(promo.valid_until) }}</p>
                <p v-if="promo.usage_limit">
                  Usage: {{ promo.used_count || 0 }} / {{ promo.usage_limit }}
                </p>
                <p v-else>Usage: {{ promo.used_count || 0 }} (unlimited)</p>
              </div>
            </div>
          </div>
          
          <div v-else class="text-center py-8 text-gray-500">
            No promo codes yet
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { promoCodesAPI } from '@/services/api'
import { generateRandomCode, formatDate } from '@/utils/helpers'
import { useToast } from '@/utils/toast'
import LoadingSpinner from '@/components/LoadingSpinner.vue'

const { showToast } = useToast()
const promoCodes = ref([])
const loading = ref(false)

const form = reactive({
  code: '',
  discount: 10,
  valid_from: '',
  valid_until: '',
  usage_limit: null,
})

onMounted(async () => {
  await fetchPromoCodes()
})

const fetchPromoCodes = async () => {
  loading.value = true
  try {
    const response = await promoCodesAPI.getAll()
    promoCodes.value = response.data
  } catch (error) {
    showToast('Failed to load promo codes', 'error')
  } finally {
    loading.value = false
  }
}

const generateCode = () => {
  form.code = generateRandomCode()
}

const createPromoCode = async () => {
  loading.value = true
  try {
    await promoCodesAPI.create(form)
    showToast('Promo code created successfully', 'success')
    Object.assign(form, { code: '', discount: 10, valid_from: '', valid_until: '', usage_limit: null })
    await fetchPromoCodes()
  } catch (error) {
    showToast('Failed to create promo code', 'error')
  } finally {
    loading.value = false
  }
}

const deletePromo = async (id) => {
  if (confirm('Delete this promo code?')) {
    try {
      await promoCodesAPI.delete(id)
      showToast('Promo code deleted', 'success')
      await fetchPromoCodes()
    } catch (error) {
      showToast('Failed to delete promo code', 'error')
    }
  }
}
</script>
