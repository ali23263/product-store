<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useOrdersStore } from '@/stores/orders'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import AlertMessage from '@/components/common/AlertMessage.vue'

const route = useRoute()
const ordersStore = useOrdersStore()

const newStatus = ref('')
const updateMessage = ref('')
const updateMessageType = ref('info')

const statusColors = {
  pending: 'bg-yellow-100 text-yellow-800',
  processing: 'bg-blue-100 text-blue-800',
  shipped: 'bg-purple-100 text-purple-800',
  delivered: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800'
}

onMounted(async () => {
  await ordersStore.fetchOrder(route.params.id)
  if (ordersStore.currentOrder) {
    newStatus.value = ordersStore.currentOrder.status
  }
})

const updateStatus = async () => {
  updateMessage.value = ''
  
  try {
    await ordersStore.updateOrderStatus(route.params.id, newStatus.value)
    updateMessage.value = 'Order status updated successfully'
    updateMessageType.value = 'success'
  } catch (err) {
    updateMessage.value = err.response?.data?.message || 'Failed to update order status'
    updateMessageType.value = 'error'
  }
}
</script>

<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <LoadingSpinner v-if="ordersStore.loading" />

    <div v-else-if="ordersStore.currentOrder">
      <!-- Header -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h1 class="text-2xl font-bold">Order #{{ ordersStore.currentOrder.id }}</h1>
            <p class="text-gray-600">Placed on {{ new Date(ordersStore.currentOrder.created_at).toLocaleDateString() }}</p>
          </div>
          
          <span 
            :class="[statusColors[ordersStore.currentOrder.status], 'px-4 py-2 rounded-full text-sm font-medium']"
          >
            {{ ordersStore.currentOrder.status }}
          </span>
        </div>

        <div class="border-t pt-4">
          <div class="text-2xl font-bold text-primary-600">
            Total: ${{ parseFloat(ordersStore.currentOrder.total).toFixed(2) }}
          </div>
        </div>
      </div>

      <!-- Status Update -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Update Order Status</h2>

        <AlertMessage 
          v-if="updateMessage" 
          :type="updateMessageType" 
          :message="updateMessage"
          dismissible
          @dismiss="updateMessage = ''"
          class="mb-4"
        />

        <div class="flex gap-4">
          <select 
            v-model="newStatus"
            class="flex-grow px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
          >
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>

          <button 
            @click="updateStatus"
            class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700"
          >
            Update Status
          </button>
        </div>
      </div>

      <!-- Customer Info -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Customer Information</h2>
        <div class="space-y-2 text-gray-700">
          <p><span class="font-medium">Name:</span> {{ ordersStore.currentOrder.user?.name }}</p>
          <p><span class="font-medium">Email:</span> {{ ordersStore.currentOrder.user?.email }}</p>
        </div>
      </div>

      <!-- Shipping Info -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
        <div class="space-y-2 text-gray-700">
          <p>{{ ordersStore.currentOrder.shipping_address }}</p>
          <p>{{ ordersStore.currentOrder.city }}, {{ ordersStore.currentOrder.state }} {{ ordersStore.currentOrder.zip_code }}</p>
          <p>Phone: {{ ordersStore.currentOrder.phone }}</p>
        </div>
      </div>

      <!-- Order Items -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Order Items</h2>
        <div class="space-y-4">
          <div 
            v-for="item in ordersStore.currentOrder.items" 
            :key="item.id"
            class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg"
          >
            <img 
              :src="item.product?.image || 'https://via.placeholder.com/80'" 
              :alt="item.product?.name"
              class="w-20 h-20 object-cover rounded"
            >
            
            <div class="flex-grow">
              <h3 class="font-semibold">{{ item.product?.name }}</h3>
              <p class="text-gray-600">Quantity: {{ item.quantity }}</p>
              <p class="text-primary-600 font-bold">${{ parseFloat(item.price).toFixed(2) }} each</p>
            </div>
            
            <div class="font-bold text-gray-900">
              ${{ (parseFloat(item.price) * item.quantity).toFixed(2) }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-16">
      <p class="text-gray-600 text-lg">Order not found</p>
    </div>
  </div>
</template>
