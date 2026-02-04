import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useToast } from '@/utils/toast'

export const useCartStore = defineStore('cart', () => {
  const items = ref([])
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

  const loadCart = () => {
    const savedCart = localStorage.getItem('cart')
    if (savedCart) {
      items.value = JSON.parse(savedCart)
    }
  }

  const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(items.value))
  }

  const addItem = (product, quantity = 1) => {
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

  const removeItem = (productId) => {
    const item = items.value.find(item => item.id === productId)
    items.value = items.value.filter(item => item.id !== productId)
    saveCart()
    showToast(`${item?.name || 'Item'} removed from cart`, 'success')
  }

  const updateQuantity = (productId, quantity) => {
    const item = items.value.find(item => item.id === productId)
    if (item) {
      if (quantity <= 0) {
        removeItem(productId)
      } else if (quantity <= item.stock) {
        item.quantity = quantity
        saveCart()
      } else {
        showToast(`Only ${item.stock} items available`, 'error')
      }
    }
  }

  const incrementQuantity = (productId) => {
    const item = items.value.find(item => item.id === productId)
    if (item && item.quantity < item.stock) {
      item.quantity++
      saveCart()
    } else {
      showToast('Maximum stock reached', 'error')
    }
  }

  const decrementQuantity = (productId) => {
    const item = items.value.find(item => item.id === productId)
    if (item) {
      if (item.quantity > 1) {
        item.quantity--
        saveCart()
      } else {
        removeItem(productId)
      }
    }
  }

  const clearCart = () => {
    items.value = []
    localStorage.removeItem('cart')
  }

  return {
    items,
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
  }
})
