<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useOrdersStore } from '@/stores/orders'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

const router = useRouter()
const ordersStore = useOrdersStore()

const statusFilter = ref('all')

const filteredOrders = computed(() => {
  if (statusFilter.value === 'all') {
    return ordersStore.orders
  }
  return ordersStore.orders.filter(order => order.status === statusFilter.value)
})

const statusColors = {
  pending: 'bg-yellow-100 text-yellow-800',
  processing: 'bg-blue-100 text-blue-800',
  shipped: 'bg-purple-100 text-purple-800',
  delivered: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800'
}

onMounted(async () => {
  await ordersStore.fetchPickerOrders()
})

const viewOrder = (id) => {
  router.push(`/picker/orders/${id}`)
}
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Orders to Pick</h1>

    <!-- Status Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
      <select 
        v-model="statusFilter"
        class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
      >
        <option value="all">All Orders</option>
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
      </select>
    </div>

    <LoadingSpinner v-if="ordersStore.loading" />

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="filteredOrders.length === 0" class="text-center py-8 text-gray-600">
        No orders to pick
      </div>

      <table v-else class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="order in filteredOrders" :key="order.id">
            <td class="px-6 py-4 whitespace-nowrap font-medium">#{{ order.id }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ order.items?.length || 0 }} items</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="[statusColors[order.status], 'px-2 py-1 text-xs rounded-full']">
                {{ order.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ new Date(order.created_at).toLocaleDateString() }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button 
                @click="viewOrder(order.id)"
                class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700"
              >
                Pick Order
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
