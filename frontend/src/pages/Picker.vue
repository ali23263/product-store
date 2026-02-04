<template>
  <div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Order Picker Dashboard</h1>

    <!-- Filter Tabs -->
    <div class="mb-6">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            v-for="status in statusFilters"
            :key="status.value"
            @click="filterStatus = status.value"
            :class="[
              'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
              filterStatus === status.value
                ? 'border-primary-500 text-primary-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            {{ status.label }}
            <span 
              v-if="getCountByStatus(status.value) > 0"
              :class="[
                'ml-2 py-0.5 px-2 rounded-full text-xs',
                filterStatus === status.value
                  ? 'bg-primary-100 text-primary-600'
                  : 'bg-gray-100 text-gray-600'
              ]"
            >
              {{ getCountByStatus(status.value) }}
            </span>
          </button>
        </nav>
      </div>
    </div>

    <!-- Orders List -->
    <div v-if="loading" class="flex justify-center py-12">
      <LoadingSpinner />
    </div>

    <div v-else-if="filteredOrders.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div 
        v-for="order in filteredOrders" 
        :key="order.id"
        class="bg-white rounded-lg shadow-lg p-6"
      >
        <!-- Order Header -->
        <div class="flex items-start justify-between mb-4">
          <div>
            <h3 class="text-xl font-bold text-gray-900">Order #{{ order.id }}</h3>
            <p class="text-sm text-gray-600">{{ formatDateTime(order.created_at) }}</p>
            <p class="text-sm text-gray-600">Customer: {{ order.user?.name || 'N/A' }}</p>
          </div>
          <span :class="statusClasses[order.status]" class="px-3 py-1 rounded-full text-xs font-semibold">
            {{ order.status }}
          </span>
        </div>

        <!-- Order Items -->
        <div class="mb-4">
          <h4 class="font-semibold text-gray-900 mb-2">Items to Pick:</h4>
          <div class="space-y-2">
            <div 
              v-for="item in order.items" 
              :key="item.id"
              class="flex items-center justify-between bg-gray-50 rounded p-3"
            >
              <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                  <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                  </svg>
                </div>
                <div>
                  <p class="font-medium text-gray-900">{{ item.product_name || item.name }}</p>
                  <p class="text-sm text-gray-600">Quantity: {{ item.quantity }}</p>
                </div>
              </div>
              <p class="font-semibold text-gray-900">{{ formatCurrency(item.price * item.quantity) }}</p>
            </div>
          </div>
        </div>

        <!-- Total -->
        <div class="border-t pt-4 mb-4">
          <div class="flex justify-between items-center">
            <span class="font-semibold text-gray-900">Total Amount</span>
            <span class="text-xl font-bold text-primary-600">{{ formatCurrency(order.total) }}</span>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-2 gap-3">
          <button 
            v-if="order.status === 'pending'"
            @click="updateOrderStatus(order, 'processing')"
            :disabled="ordersStore.loading"
            class="btn-primary"
          >
            Start Processing
          </button>
          
          <button 
            v-if="order.status === 'processing'"
            @click="updateOrderStatus(order, 'completed')"
            :disabled="ordersStore.loading"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors"
          >
            Mark as Completed
          </button>

          <button 
            @click="viewOrderDetails(order)"
            class="btn-secondary"
          >
            View Details
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12">
      <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <h3 class="text-xl font-semibold text-gray-700 mb-2">No orders to process</h3>
      <p class="text-gray-500">All orders in this status have been processed</p>
    </div>

    <!-- Order Details Modal -->
    <Modal v-model="showModal" :title="`Order #${selectedOrder?.id} Details`">
      <div v-if="selectedOrder" class="space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <span class="text-gray-600">Customer:</span>
            <p class="font-medium">{{ selectedOrder.user?.name }}</p>
            <p class="text-xs text-gray-500">{{ selectedOrder.user?.email }}</p>
          </div>
          <div>
            <span class="text-gray-600">Order Date:</span>
            <p class="font-medium">{{ formatDateTime(selectedOrder.created_at) }}</p>
          </div>
          <div>
            <span class="text-gray-600">Status:</span>
            <p class="font-medium capitalize">{{ selectedOrder.status }}</p>
          </div>
          <div>
            <span class="text-gray-600">Total Items:</span>
            <p class="font-medium">{{ selectedOrder.items?.length || 0 }}</p>
          </div>
        </div>
        
        <div>
          <h4 class="font-semibold mb-2">Order Items</h4>
          <div class="space-y-2">
            <div v-for="item in selectedOrder.items" :key="item.id" class="flex justify-between text-sm bg-gray-50 p-2 rounded">
              <span>{{ item.quantity }}x {{ item.product_name || item.name }}</span>
              <span class="font-medium">{{ formatCurrency(item.price * item.quantity) }}</span>
            </div>
          </div>
        </div>
        
        <div class="border-t pt-4">
          <div class="flex justify-between font-bold text-lg">
            <span>Total</span>
            <span class="text-primary-600">{{ formatCurrency(selectedOrder.total) }}</span>
          </div>
        </div>
      </div>
      
      <template #footer>
        <button @click="showModal = false" class="w-full btn-secondary">Close</button>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useOrdersStore } from '@/stores/orders'
import { formatCurrency, formatDateTime } from '@/utils/helpers'
import Modal from '@/components/Modal.vue'
import LoadingSpinner from '@/components/LoadingSpinner.vue'

const ordersStore = useOrdersStore()
const loading = ref(false)
const filterStatus = ref('all')
const showModal = ref(false)
const selectedOrder = ref(null)

const statusFilters = [
  { value: 'all', label: 'All Orders' },
  { value: 'pending', label: 'Pending' },
  { value: 'processing', label: 'Processing' },
  { value: 'completed', label: 'Completed' },
  { value: 'cancelled', label: 'Cancelled' },
]

const statusClasses = {
  pending: 'bg-yellow-100 text-yellow-800',
  processing: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800',
}

const filteredOrders = computed(() => {
  if (filterStatus.value === 'all') {
    return ordersStore.orders
  }
  return ordersStore.orders.filter(order => order.status === filterStatus.value)
})

const getCountByStatus = (status) => {
  if (status === 'all') {
    return ordersStore.orders.length
  }
  return ordersStore.orders.filter(order => order.status === status).length
}

onMounted(async () => {
  loading.value = true
  try {
    await ordersStore.fetchOrders()
  } finally {
    loading.value = false
  }
})

const updateOrderStatus = async (order, newStatus) => {
  await ordersStore.updateOrderStatus(order.id, newStatus)
}

const viewOrderDetails = (order) => {
  selectedOrder.value = order
  showModal.value = true
}
</script>
