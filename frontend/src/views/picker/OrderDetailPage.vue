<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useOrdersStore } from '@/stores/orders'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import AlertMessage from '@/components/common/AlertMessage.vue'

const route = useRoute()
const router = useRouter()
const ordersStore = useOrdersStore()

const pickedItems = ref(new Set())
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
})

const toggleItemPicked = (itemId) => {
  if (pickedItems.value.has(itemId)) {
    pickedItems.value.delete(itemId)
  } else {
    pickedItems.value.add(itemId)
  }
}

const allItemsPicked = () => {
  if (!ordersStore.currentOrder?.items) return false
  return ordersStore.currentOrder.items.every(item => pickedItems.value.has(item.id))
}

const markAsProcessing = async () => {
  updateMessage.value = ''
  
  try {
    await ordersStore.updateOrderStatus(route.params.id, 'processing')
    updateMessage.value = 'Order marked as processing'
    updateMessageType.value = 'success'
  } catch (err) {
    updateMessage.value = err.response?.data?.message || 'Failed to update order status'
    updateMessageType.value = 'error'
  }
}

const markAsShipped = async () => {
  if (!allItemsPicked()) {
    updateMessage.value = 'Please pick all items before marking as shipped'
    updateMessageType.value = 'error'
    return
  }

  updateMessage.value = ''
  
  try {
    await ordersStore.updateOrderStatus(route.params.id, 'shipped')
    updateMessage.value = 'Order marked as shipped'
    updateMessageType.value = 'success'
    
    setTimeout(() => {
      router.push('/picker/orders')
    }, 2000)
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

        <!-- Progress -->
        <div class="mt-4">
          <div class="flex justify-between text-sm mb-2">
            <span>Picking Progress</span>
            <span>{{ pickedItems.size }} / {{ ordersStore.currentOrder.items?.length || 0 }}</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div 
              class="bg-primary-600 h-2 rounded-full transition-all"
              :style="{ width: `${(pickedItems.size / (ordersStore.currentOrder.items?.length || 1)) * 100}%` }"
            ></div>
          </div>
        </div>
      </div>

      <AlertMessage 
        v-if="updateMessage" 
        :type="updateMessageType" 
        :message="updateMessage"
        dismissible
        @dismiss="updateMessage = ''"
        class="mb-4"
      />

      <!-- Shipping Info -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Shipping Information</h2>
        <div class="space-y-2 text-gray-700">
          <p>{{ ordersStore.currentOrder.shipping_address }}</p>
          <p>{{ ordersStore.currentOrder.city }}, {{ ordersStore.currentOrder.state }} {{ ordersStore.currentOrder.zip_code }}</p>
          <p>Phone: {{ ordersStore.currentOrder.phone }}</p>
        </div>
      </div>

      <!-- Items to Pick -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Items to Pick</h2>
        <div class="space-y-4">
          <div 
            v-for="item in ordersStore.currentOrder.items" 
            :key="item.id"
            @click="toggleItemPicked(item.id)"
            :class="[
              'flex items-center gap-4 p-4 border-2 rounded-lg cursor-pointer transition',
              pickedItems.has(item.id) ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-primary-300'
            ]"
          >
            <div class="flex-shrink-0">
              <div 
                :class="[
                  'w-8 h-8 rounded-full border-2 flex items-center justify-center',
                  pickedItems.has(item.id) ? 'border-green-500 bg-green-500' : 'border-gray-300'
                ]"
              >
                <svg v-if="pickedItems.has(item.id)" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            </div>

            <img 
              :src="item.product?.image || 'https://via.placeholder.com/80'" 
              :alt="item.product?.name"
              class="w-20 h-20 object-cover rounded"
            >
            
            <div class="flex-grow">
              <h3 class="font-semibold">{{ item.product?.name }}</h3>
              <p class="text-gray-600">Quantity: <span class="font-bold">{{ item.quantity }}</span></p>
              <p class="text-sm text-gray-500">Location: {{ item.product?.location || 'Aisle 1' }}</p>
            </div>
            
            <div class="text-right">
              <span 
                :class="[
                  'px-3 py-1 rounded-full text-sm font-medium',
                  pickedItems.has(item.id) ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'
                ]"
              >
                {{ pickedItems.has(item.id) ? 'Picked' : 'Not Picked' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <button 
            v-if="ordersStore.currentOrder.status === 'pending'"
            @click="markAsProcessing"
            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700"
          >
            Start Picking
          </button>

          <button 
            @click="markAsShipped"
            :disabled="!allItemsPicked()"
            class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Mark as Shipped
          </button>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-16">
      <p class="text-gray-600 text-lg">Order not found</p>
    </div>
  </div>
</template>
