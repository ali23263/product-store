<script setup>
import { RouterLink } from 'vue-router'

const props = defineProps({
  order: {
    type: Object,
    required: true
  }
})

const statusColors = {
  pending: 'bg-yellow-100 text-yellow-800',
  processing: 'bg-blue-100 text-blue-800',
  shipped: 'bg-purple-100 text-purple-800',
  delivered: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800'
}
</script>

<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-4">
      <div>
        <h3 class="text-lg font-semibold">Order #{{ order.id }}</h3>
        <p class="text-sm text-gray-600">{{ new Date(order.created_at).toLocaleDateString() }}</p>
      </div>
      
      <span 
        :class="[statusColors[order.status], 'px-3 py-1 rounded-full text-sm font-medium']"
      >
        {{ order.status }}
      </span>
    </div>

    <div class="space-y-2 mb-4">
      <div v-if="order.items" class="text-sm text-gray-700">
        {{ order.items.length }} item(s)
      </div>
      
      <div class="text-xl font-bold text-primary-600">
        ${{ parseFloat(order.total).toFixed(2) }}
      </div>
    </div>

    <RouterLink 
      :to="`/orders/${order.id}`"
      class="block text-center bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700"
    >
      View Details
    </RouterLink>
  </div>
</template>
