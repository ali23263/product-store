import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'
import { useAuthStore } from './auth'

export const useCartStore = defineStore('cart', () => {
  const items = ref([])
  const loading = ref(false)
  const promoCode = ref('')
  const discount = ref(0)

  const itemCount = computed(() => {
    return items.value.reduce((total, item) => total + item.quantity, 0)
  })

  const subtotal = computed(() => {
    return items.value.reduce((total, item) => {
      return total + (item.price * item.quantity)
    }, 0)
  })

  const total = computed(() => {
    return subtotal.value - discount.value
  })

  function loadFromStorage() {
    const storedCart = localStorage.getItem('cart')
    if (storedCart) {
      items.value = JSON.parse(storedCart)
    }
  }

  function saveToStorage() {
    localStorage.setItem('cart', JSON.stringify(items.value))
  }

  async function syncCart() {
    const authStore = useAuthStore()
    
    if (!authStore.isAuthenticated) {
      return
    }

    try {
      // Send local cart to server
      if (items.value.length > 0) {
        await api.post('/cart/sync', { items: items.value })
      }
      
      // Fetch server cart
      const response = await api.get('/cart')
      items.value = response.items || []
      saveToStorage()
    } catch (err) {
      console.error('Failed to sync cart:', err)
    }
  }

  async function addItem(product, quantity = 1) {
    const existingItem = items.value.find(item => item.product_id === product.id)
    
    if (existingItem) {
      existingItem.quantity += quantity
    } else {
      items.value.push({
        product_id: product.id,
        name: product.name,
        price: product.price,
        image: product.image,
        quantity: quantity
      })
    }

    saveToStorage()

    const authStore = useAuthStore()
    if (authStore.isAuthenticated) {
      try {
        await api.post('/cart/items', {
          product_id: product.id,
          quantity: quantity
        })
      } catch (err) {
        console.error('Failed to add item to server cart:', err)
      }
    }
  }

  async function updateQuantity(productId, quantity) {
    const item = items.value.find(item => item.product_id === productId)
    
    if (item) {
      if (quantity <= 0) {
        await removeItem(productId)
      } else {
        item.quantity = quantity
        saveToStorage()

        const authStore = useAuthStore()
        if (authStore.isAuthenticated) {
          try {
            await api.put(`/cart/items/${productId}`, { quantity })
          } catch (err) {
            console.error('Failed to update item quantity:', err)
          }
        }
      }
    }
  }

  async function removeItem(productId) {
    items.value = items.value.filter(item => item.product_id !== productId)
    saveToStorage()

    const authStore = useAuthStore()
    if (authStore.isAuthenticated) {
      try {
        await api.delete(`/cart/items/${productId}`)
      } catch (err) {
        console.error('Failed to remove item from server cart:', err)
      }
    }
  }

  async function applyPromoCode(code) {
    try {
      const response = await api.post('/promo-codes/validate', { code })
      
      if (response.valid) {
        promoCode.value = code
        discount.value = response.discount_amount || 0
        return { success: true, message: 'Promo code applied!' }
      } else {
        return { success: false, message: 'Invalid promo code' }
      }
    } catch (err) {
      return { success: false, message: err.response?.data?.message || 'Failed to apply promo code' }
    }
  }

  function clearPromoCode() {
    promoCode.value = ''
    discount.value = 0
  }

  function clearCart() {
    items.value = []
    promoCode.value = ''
    discount.value = 0
    localStorage.removeItem('cart')
  }

  // Load from storage on init
  loadFromStorage()

  return {
    items,
    loading,
    promoCode,
    discount,
    itemCount,
    subtotal,
    total,
    addItem,
    updateQuantity,
    removeItem,
    applyPromoCode,
    clearPromoCode,
    clearCart,
    syncCart
  }
})
