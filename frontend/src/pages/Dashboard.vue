<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Welcome Section -->
      <div class="bg-gradient-to-r from-primary-600 to-purple-600 rounded-lg shadow-lg p-8 mb-8 text-white">
        <h1 class="text-3xl font-bold mb-2">Welcome back, {{ authStore.user?.name }}!</h1>
        <p class="text-purple-100">Manage your orders and account settings</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Orders Section -->
          <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
              <h2 class="text-2xl font-bold text-gray-900">Order History</h2>
            </div>
            
            <div class="p-6">
              <!-- Loading State -->
              <div v-if="ordersStore.loading" class="flex justify-center py-12">
                <LoadingSpinner />
              </div>

              <!-- Orders List -->
              <div v-else-if="ordersStore.orders.length > 0" class="space-y-4">
                <OrderCard 
                  v-for="order in ordersStore.orders" 
                  :key="order.id" 
                  :order="order"
                  @view-details="showOrderDetails"
                />
              </div>

              <!-- Empty State -->
              <div v-else class="text-center py-12">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No orders yet</h3>
                <p class="text-gray-500 mb-6">Start shopping to see your orders here</p>
                <router-link to="/" class="btn-primary">
                  Start Shopping
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
          <!-- Promo Code Section -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Have a Promo Code?</h3>
            <div class="space-y-3">
              <input
                v-model="promoCode"
                type="text"
                placeholder="Enter promo code"
                class="input-field"
                :disabled="!!ordersStore.appliedPromo"
              />
              <button 
                v-if="!ordersStore.appliedPromo"
                @click="applyPromo"
                :disabled="!promoCode || ordersStore.loading"
                class="w-full btn-primary"
              >
                Apply Code
              </button>
              <div v-else class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                  <span class="font-semibold text-green-800">{{ ordersStore.appliedPromo.code }}</span>
                  <button 
                    @click="removePromo"
                    class="text-green-600 hover:text-green-800"
                  >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
                <p class="text-sm text-green-700">{{ ordersStore.appliedPromo.discount }}% discount applied</p>
              </div>
            </div>
          </div>

          <!-- Account Info -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Account Information</h3>
            <div class="space-y-3">
              <div>
                <label class="text-sm text-gray-600">Name</label>
                <p class="font-medium text-gray-900">{{ authStore.user?.name }}</p>
              </div>
              <div>
                <label class="text-sm text-gray-600">Email</label>
                <p class="font-medium text-gray-900">{{ authStore.user?.email }}</p>
              </div>
              <div>
                <label class="text-sm text-gray-600">Account Type</label>
                <p class="font-medium text-gray-900 capitalize">{{ authStore.user?.role }}</p>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-2">
              <router-link to="/" class="block w-full btn-secondary text-center">
                Browse Products
              </router-link>
              <router-link to="/cart" class="block w-full btn-secondary text-center">
                View Cart
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Details Modal -->
    <Modal v-model="showModal" :title="`Order #${selectedOrder?.id}`">
      <div v-if="selectedOrder" class="space-y-4">
        <div>
          <h4 class="font-semibold text-gray-900 mb-2">Order Details</h4>
          <div class="text-sm space-y-1">
            <p><span class="text-gray-600">Date:</span> {{ formatDateTime(selectedOrder.created_at) }}</p>
            <p><span class="text-gray-600">Status:</span> <span class="capitalize">{{ selectedOrder.status }}</span></p>
          </div>
        </div>

        <div>
          <h4 class="font-semibold text-gray-900 mb-2">Items</h4>
          <div class="space-y-2">
            <div 
              v-for="item in selectedOrder.items" 
              :key="item.id"
              class="flex justify-between text-sm"
            >
              <span>{{ item.quantity }}x {{ item.product_name || item.name }}</span>
              <span class="font-medium">{{ formatCurrency(item.price * item.quantity) }}</span>
            </div>
          </div>
        </div>

        <div class="border-t pt-4">
          <div class="flex justify-between mb-2">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-medium">{{ formatCurrency(selectedOrder.subtotal || selectedOrder.total) }}</span>
          </div>
          <div v-if="selectedOrder.discount" class="flex justify-between mb-2 text-green-600">
            <span>Discount</span>
            <span>-{{ formatCurrency(selectedOrder.discount) }}</span>
          </div>
          <div class="flex justify-between font-bold text-lg">
            <span>Total</span>
            <span class="text-primary-600">{{ formatCurrency(selectedOrder.total) }}</span>
          </div>
        </div>
      </div>

      <template #footer>
        <button @click="showModal = false" class="w-full btn-secondary">
          Close
        </button>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useOrdersStore } from '@/stores/orders'
import { formatCurrency, formatDateTime } from '@/utils/helpers'
import OrderCard from '@/components/OrderCard.vue'
import Modal from '@/components/Modal.vue'
import LoadingSpinner from '@/components/LoadingSpinner.vue'

const authStore = useAuthStore()
const ordersStore = useOrdersStore()

const promoCode = ref('')
const showModal = ref(false)
const selectedOrder = ref(null)

onMounted(() => {
  ordersStore.fetchOrders()
})

const applyPromo = async () => {
  if (!promoCode.value) return
  
  try {
    await ordersStore.applyPromoCode(promoCode.value)
  } catch (error) {
    console.error('Failed to apply promo code:', error)
  }
}

const removePromo = () => {
  ordersStore.removePromoCode()
  promoCode.value = ''
}

const showOrderDetails = (order) => {
  selectedOrder.value = order
  showModal.value = true
}
</script>
