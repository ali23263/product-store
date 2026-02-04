<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import { useOrdersStore } from '@/stores/orders'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

const ordersStore = useOrdersStore()

const stats = ref({
  total_sales: 0,
  total_orders: 0,
  total_customers: 0,
  pending_orders: 0
})

const loading = ref(false)

onMounted(async () => {
  loading.value = true
  try {
    const response = await api.get('/admin/stats')
    stats.value = response.data || response
    
    await ordersStore.fetchAllOrders()
  } catch (err) {
    console.error('Failed to fetch stats:', err)
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <LoadingSpinner v-if="loading" />

    <div v-else>
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-primary-100 rounded-lg p-3">
              <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Sales</p>
              <p class="text-2xl font-bold text-gray-900">${{ parseFloat(stats.total_sales || 0).toFixed(2) }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Orders</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.total_orders || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Customers</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.total_customers || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
              <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Pending Orders</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.pending_orders || 0 }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <router-link to="/admin/products" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
          <h3 class="text-lg font-semibold mb-2">Manage Products</h3>
          <p class="text-gray-600">Add, edit, or delete products</p>
        </router-link>

        <router-link to="/admin/orders" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
          <h3 class="text-lg font-semibold mb-2">View Orders</h3>
          <p class="text-gray-600">Process and manage orders</p>
        </router-link>

        <router-link to="/admin/promo-codes" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
          <h3 class="text-lg font-semibold mb-2">Promo Codes</h3>
          <p class="text-gray-600">Create and manage promo codes</p>
        </router-link>
      </div>

      <!-- Recent Orders -->
      <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
        
        <div v-if="ordersStore.orders.length === 0" class="text-center py-8 text-gray-600">
          No orders yet
        </div>

        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="order in ordersStore.orders.slice(0, 10)" :key="order.id">
                <td class="px-6 py-4 whitespace-nowrap">#{{ order.id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ order.user?.name || 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">${{ parseFloat(order.total).toFixed(2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                    {{ order.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ new Date(order.created_at).toLocaleDateString() }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <router-link 
                    :to="`/admin/orders/${order.id}`"
                    class="text-primary-600 hover:text-primary-800"
                  >
                    View
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
