import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useToast } from '@/utils/toast'
import { cartAPI } from '@/services/api'

export const useCartStore = defineStore('cart', () => {
  const items = ref([])
  const loading = ref(false)
  const { showToast } = useToast()

  const itemCount = computed(() => {
    return items.value.reduce((total, item) => total + item.quantity, 0)
  })

  const subtotal = computed(() => {
    return items.value.reduce((total, item) => {
      return total + (item.price * item.quantity)
    }, 0)
  })

  const total = computed(() => subtotal.value)

  // Check if user is authenticated (without importing auth store to avoid circular dependency)
  const isAuthenticated = () => {
    return !!localStorage.getItem('auth_token')
  }

  const loadCart = async () => {
    if (isAuthenticated()) {
      // Load from server
      try {
        loading.value = true
        const response = await cartAPI.getCart()
        items.value = response.data.items?.map(item => ({
          id: item.product_id,
          cartItemId: item.id,
          name: item.product?.name || item.product_name,
          price: parseFloat(item.product?.price || item.price),
          image: item.product?.image,
          quantity: item.quantity,
          stock: item.product?.stock || 999,
        })) || []
      } catch (error) {
        console.error('Failed to load cart from server:', error)
        // Fallback to localStorage
        const savedCart = localStorage.getItem('cart')
        if (savedCart) {
          items.value = JSON.parse(savedCart)
        }
      } finally {
        loading.value = false
      }
    } else {
      // Load from localStorage
      const savedCart = localStorage.getItem('cart')
      if (savedCart) {
        items.value = JSON.parse(savedCart)
      }
    }
  }

  const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(items.value))
  }

  const addItem = async (product, quantity = 1) => {
    if (isAuthenticated()) {
      // Add to server cart
      try {
        loading.value = true
        const response = await cartAPI.addItem(product.id, quantity)
        
        const existingItem = items.value.find(item => item.id === product.id)
        if (existingItem) {
          existingItem.quantity += quantity
          existingItem.cartItemId = response.data.id
          showToast(`Updated ${product.name} quantity`, 'success')
        } else {
          items.value.push({
            id: product.id,
            cartItemId: response.data.id,
            name: product.name,
            price: product.price,
            image: product.image,
            quantity: quantity,
            stock: product.stock,
          })
          showToast(`${product.name} added to cart`, 'success')
        }
        saveCart()
      } catch (error) {
        console.error('Failed to add item to cart:', error)
        showToast('Failed to add item to cart', 'error')
      } finally {
        loading.value = false
      }
    } else {
      // Local cart for non-authenticated users
      const existingItem = items.value.find(item => item.id === product.id)
      
      if (existingItem) {
        existingItem.quantity += quantity
        showToast(`Updated ${product.name} quantity`, 'success')
      } else {
        items.value.push({
          id: product.id,
          name: product.name,
          price: product.price,
          image: product.image,
          quantity: quantity,
          stock: product.stock,
        })
        showToast(`${product.name} added to cart`, 'success')
      }
      
      saveCart()
    }
  }

  const removeItem = async (productId) => {
    const item = items.value.find(item => item.id === productId)
    
    if (isAuthenticated() && item?.cartItemId) {
      try {
        loading.value = true
        await cartAPI.removeItem(item.cartItemId)
        items.value = items.value.filter(item => item.id !== productId)
        saveCart()
        showToast(`${item?.name || 'Item'} removed from cart`, 'success')
      } catch (error) {
        console.error('Failed to remove item from cart:', error)
        showToast('Failed to remove item', 'error')
      } finally {
        loading.value = false
      }
    } else {
      items.value = items.value.filter(item => item.id !== productId)
      saveCart()
      showToast(`${item?.name || 'Item'} removed from cart`, 'success')
    }
  }

  const updateQuantity = async (productId, quantity) => {
    const item = items.value.find(item => item.id === productId)
    if (!item) return
    
    if (quantity <= 0) {
      await removeItem(productId)
      return
    }
    
    if (quantity > item.stock) {
      showToast(`Only ${item.stock} items available`, 'error')
      return
    }
    
    if (isAuthenticated() && item.cartItemId) {
      try {
        loading.value = true
        await cartAPI.updateItem(item.cartItemId, quantity)
        item.quantity = quantity
        saveCart()
      } catch (error) {
        console.error('Failed to update item quantity:', error)
        showToast('Failed to update quantity', 'error')
      } finally {
        loading.value = false
      }
    } else {
      item.quantity = quantity
      saveCart()
    }
  }

  const incrementQuantity = async (productId) => {
    const item = items.value.find(item => item.id === productId)
    if (item && item.quantity < item.stock) {
      await updateQuantity(productId, item.quantity + 1)
    } else {
      showToast('Maximum stock reached', 'error')
    }
  }

  const decrementQuantity = async (productId) => {
    const item = items.value.find(item => item.id === productId)
    if (item) {
      if (item.quantity > 1) {
        await updateQuantity(productId, item.quantity - 1)
      } else {
        await removeItem(productId)
      }
    }
  }

  const clearCart = async () => {
    if (isAuthenticated()) {
      try {
        loading.value = true
        await cartAPI.clearCart()
      } catch (error) {
        console.error('Failed to clear cart on server:', error)
      } finally {
        loading.value = false
      }
    }
    items.value = []
    localStorage.removeItem('cart')
  }

  // Sync local cart to server when user logs in
  const syncCartToServer = async () => {
    if (!isAuthenticated()) return
    
    const localCart = items.value
    if (localCart.length === 0) return
    
    try {
      loading.value = true
      // Add each local item to the server cart
      for (const item of localCart) {
        try {
          await cartAPI.addItem(item.id, item.quantity)
        } catch (error) {
          console.error(`Failed to sync item ${item.name}:`, error)
        }
      }
      // Reload cart from server to get proper cartItemIds
      await loadCart()
    } finally {
      loading.value = false
    }
  }

  return {
    items,
    loading,
    itemCount,
    subtotal,
    total,
    loadCart,
    addItem,
    removeItem,
    updateQuantity,
    incrementQuantity,
    decrementQuantity,
    clearCart,
    syncCartToServer,
  }
})
