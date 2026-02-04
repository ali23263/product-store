import { defineStore } from 'pinia'
import { ref } from 'vue'
import { ordersAPI, promoCodesAPI } from '@/services/api'
import { useCartStore } from './cart'
import { useToast } from '@/utils/toast'

export const useOrdersStore = defineStore('orders', () => {
  const orders = ref([])
  const currentOrder = ref(null)
  const appliedPromo = ref(null)
  const loading = ref(false)
  const { showToast } = useToast()

  const fetchOrders = async (params = {}) => {
    try {
      loading.value = true
      const response = await ordersAPI.getAll(params)
      orders.value = response.data.data || response.data
    } catch (error) {
      showToast('Failed to load orders', 'error')
      console.error('Fetch orders error:', error)
    } finally {
      loading.value = false
    }
  }

  const fetchPickerOrders = async (params = {}) => {
    try {
      loading.value = true
      const response = await ordersAPI.getAllPicker(params)
      orders.value = response.data.data || response.data
    } catch (error) {
      showToast('Failed to load orders', 'error')
      console.error('Fetch picker orders error:', error)
    } finally {
      loading.value = false
    }
  }

  const fetchOrderById = async (id) => {
    try {
      loading.value = true
      const response = await ordersAPI.getById(id)
      currentOrder.value = response.data
      return response.data
    } catch (error) {
      showToast('Failed to load order details', 'error')
      throw error
    } finally {
      loading.value = false
    }
  }

  const createOrder = async (orderData = {}) => {
    try {
      loading.value = true
      const cartStore = useCartStore()
      
      const data = {
        promo_code: appliedPromo.value?.code || null,
        ...orderData,
      }
      
      const response = await ordersAPI.create(data)
      // Backend returns { message, order }
      const order = response.data.order || response.data
      orders.value.unshift(order)
      await cartStore.clearCart()
      appliedPromo.value = null
      showToast('Order placed successfully!', 'success')
      return order
    } catch (error) {
      const message = error.response?.data?.message || 'Failed to create order'
      showToast(message, 'error')
      throw error
    } finally {
      loading.value = false
    }
  }

  const updateOrderStatus = async (id, status) => {
    try {
      loading.value = true
      const response = await ordersAPI.updateStatus(id, status)
      const index = orders.value.findIndex(o => o.id === id)
      if (index !== -1) {
        orders.value[index] = response.data
      }
      showToast(`Order status updated to ${status}`, 'success')
      return response.data
    } catch (error) {
      showToast('Failed to update order status', 'error')
      throw error
    } finally {
      loading.value = false
    }
  }

  const applyPromoCode = async (code) => {
    try {
      loading.value = true
      const response = await promoCodesAPI.validate(code)
      appliedPromo.value = response.data
      showToast(`Promo code applied! ${response.data.discount}% off`, 'success')
      return response.data
    } catch (error) {
      appliedPromo.value = null
      const message = error.response?.data?.message || 'Invalid promo code'
      showToast(message, 'error')
      throw error
    } finally {
      loading.value = false
    }
  }

  const removePromoCode = () => {
    appliedPromo.value = null
    showToast('Promo code removed', 'success')
  }

  return {
    orders,
    currentOrder,
    appliedPromo,
    loading,
    fetchOrders,
    fetchPickerOrders,
    fetchOrderById,
    createOrder,
    updateOrderStatus,
    applyPromoCode,
    removePromoCode,
  }
})
