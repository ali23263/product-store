<template>
  <div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
      <h2 class="text-xl font-bold text-gray-900">Orders Management</h2>
    </div>

    <div class="p-6">
      <div v-if="loading" class="flex justify-center py-8">
        <LoadingSpinner />
      </div>
      
      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="order in orders" :key="order.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">#{{ order.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">{{ order.user?.name || 'N/A' }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">{{ formatDate(order.created_at) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">{{ formatCurrency(order.total) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <select 
                  :value="order.status"
                  @change="updateStatus(order, $event.target.value)"
                  class="text-sm border rounded px-2 py-1"
                >
                  <option value="pending">Pending</option>
                  <option value="processing">Processing</option>
                  <option value="ready">Ready</option>
                  <option value="shipped">Shipped</option>
                  <option value="delivered">Delivered</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <button @click="viewDetails(order)" class="text-primary-600 hover:text-primary-900">View</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Order Details Modal -->
    <Modal v-model="showModal" :title="`Order #${selectedOrder?.id}`">
      <div v-if="selectedOrder" class="space-y-4">
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <span class="text-gray-600">Customer:</span>
            <p class="font-medium">{{ selectedOrder.user?.name }}</p>
          </div>
          <div>
            <span class="text-gray-600">Date:</span>
            <p class="font-medium">{{ formatDateTime(selectedOrder.created_at) }}</p>
          </div>
        </div>
        
        <div>
          <h4 class="font-semibold mb-2">Items</h4>
          <div class="space-y-2">
            <div v-for="item in selectedOrder.items" :key="item.id" class="flex justify-between text-sm">
              <span>{{ item.quantity }}x {{ item.product_name || item.name }}</span>
              <span>{{ formatCurrency(item.price * item.quantity) }}</span>
            </div>
          </div>
        </div>
        
        <div class="border-t pt-4">
          <div class="flex justify-between font-bold">
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
import { ref, onMounted } from 'vue'
import { ordersAPI } from '@/services/api'
import { formatCurrency, formatDate, formatDateTime } from '@/utils/helpers'
import { useToast } from '@/utils/toast'
import Modal from '@/components/Modal.vue'
import LoadingSpinner from '@/components/LoadingSpinner.vue'

const { showToast } = useToast()
const orders = ref([])
const loading = ref(false)
const showModal = ref(false)
const selectedOrder = ref(null)

onMounted(async () => {
  loading.value = true
  try {
    const response = await ordersAPI.getAll()
    orders.value = response.data.data || response.data
  } catch (error) {
    showToast('Failed to load orders', 'error')
  } finally {
    loading.value = false
  }
})

const updateStatus = async (order, newStatus) => {
  try {
    await ordersAPI.updateStatus(order.id, newStatus)
    order.status = newStatus
    showToast('Order status updated', 'success')
  } catch (error) {
    showToast('Failed to update status', 'error')
  }
}

const viewDetails = (order) => {
  selectedOrder.value = order
  showModal.value = true
}
</script>
