<template>
  <div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-start mb-4">
      <div>
        <h3 class="font-semibold text-gray-900">Order #{{ order.id }}</h3>
        <p class="text-sm text-gray-600">{{ formatDateTime(order.created_at) }}</p>
      </div>
      <span :class="statusClasses[order.status]" class="px-3 py-1 rounded-full text-xs font-semibold">
        {{ order.status }}
      </span>
    </div>

    <div class="space-y-2 mb-4">
      <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm">
        <span class="text-gray-600">{{ item.quantity }}x {{ item.product_name || item.name }}</span>
        <span class="font-medium">{{ formatCurrency(item.price * item.quantity) }}</span>
      </div>
    </div>

    <div class="border-t pt-4">
      <div class="flex justify-between items-center mb-2">
        <span class="text-gray-600">Subtotal</span>
        <span class="font-medium">{{ formatCurrency(order.subtotal || order.total) }}</span>
      </div>
      <div v-if="order.discount" class="flex justify-between items-center mb-2 text-green-600">
        <span>Discount ({{ order.promo_code }})</span>
        <span>-{{ formatCurrency(order.discount) }}</span>
      </div>
      <div class="flex justify-between items-center font-bold text-lg">
        <span>Total</span>
        <span class="text-primary-600">{{ formatCurrency(order.total) }}</span>
      </div>
    </div>

    <button 
      v-if="showDetails"
      @click="$emit('view-details', order)"
      class="w-full mt-4 btn-secondary"
    >
      View Details
    </button>
  </div>
</template>

<script setup>
import { formatCurrency, formatDateTime } from '@/utils/helpers'

defineProps({
  order: {
    type: Object,
    required: true,
  },
  showDetails: {
    type: Boolean,
    default: true,
  },
})

defineEmits(['view-details'])

const statusClasses = {
  pending: 'bg-yellow-100 text-yellow-800',
  processing: 'bg-blue-100 text-blue-800',
  ready: 'bg-purple-100 text-purple-800',
  shipped: 'bg-indigo-100 text-indigo-800',
  delivered: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800',
}
</script>
