import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/services/api'

export const useOrdersStore = defineStore('orders', () => {
  const orders = ref([])
  const currentOrder = ref(null)
  const loading = ref(false)
  const error = ref(null)

  async function fetchOrders() {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.get('/orders')
      orders.value = response.data || response
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch orders'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchOrder(id) {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.get(`/orders/${id}`)
      currentOrder.value = response.data || response
      return currentOrder.value
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch order'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function createOrder(orderData) {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.post('/orders', orderData)
      currentOrder.value = response.data || response
      return currentOrder.value
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to create order'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateOrderStatus(id, status) {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.patch(`/orders/${id}/status`, { status })
      
      // Update in list if present
      const index = orders.value.findIndex(o => o.id === id)
      if (index !== -1) {
        orders.value[index].status = status
      }
      
      // Update current order if it's the same
      if (currentOrder.value?.id === id) {
        currentOrder.value.status = status
      }
      
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update order status'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchAllOrders() {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.get('/admin/orders')
      orders.value = response.data || response
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch all orders'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchPickerOrders() {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.get('/picker/orders')
      orders.value = response.data || response
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch picker orders'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    orders,
    currentOrder,
    loading,
    error,
    fetchOrders,
    fetchOrder,
    createOrder,
    updateOrderStatus,
    fetchAllOrders,
    fetchPickerOrders
  }
})
