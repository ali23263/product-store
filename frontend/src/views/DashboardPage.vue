<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useOrdersStore } from '@/stores/orders'
import OrderCard from '@/components/orders/OrderCard.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

const authStore = useAuthStore()
const ordersStore = useOrdersStore()

const activeTab = ref('orders')

onMounted(async () => {
  await ordersStore.fetchOrders()
})
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">My Dashboard</h1>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-8">
      <nav class="-mb-px flex space-x-8">
        <button
          @click="activeTab = 'orders'"
          :class="[
            'py-4 px-1 border-b-2 font-medium text-sm',
            activeTab === 'orders'
              ? 'border-primary-600 text-primary-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
        >
          Orders
        </button>
        <button
          @click="activeTab = 'profile'"
          :class="[
            'py-4 px-1 border-b-2 font-medium text-sm',
            activeTab === 'profile'
              ? 'border-primary-600 text-primary-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
        >
          Profile
        </button>
      </nav>
    </div>

    <!-- Orders Tab -->
    <div v-if="activeTab === 'orders'">
      <LoadingSpinner v-if="ordersStore.loading" />

      <div v-else-if="ordersStore.orders.length === 0" class="text-center py-16">
        <p class="text-gray-600 text-lg mb-4">No orders yet</p>
        <router-link to="/products" class="bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 inline-block">
          Start Shopping
        </router-link>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <OrderCard 
          v-for="order in ordersStore.orders" 
          :key="order.id"
          :order="order"
        />
      </div>
    </div>

    <!-- Profile Tab -->
    <div v-if="activeTab === 'profile'">
      <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <h2 class="text-xl font-semibold mb-6">Profile Information</h2>

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
            <p class="text-gray-900">{{ authStore.user?.name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <p class="text-gray-900">{{ authStore.user?.email }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
            <span class="px-3 py-1 bg-primary-100 text-primary-800 rounded-full text-sm">
              {{ authStore.user?.role }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
