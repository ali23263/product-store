import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/services/api'

export const useProductsStore = defineStore('products', () => {
  const products = ref([])
  const categories = ref([])
  const currentProduct = ref(null)
  const loading = ref(false)
  const error = ref(null)
  
  // Filters
  const filters = ref({
    search: '',
    category_id: null,
    min_price: null,
    max_price: null,
    sort: 'name'
  })

  const filteredProducts = computed(() => {
    let result = [...products.value]

    if (filters.value.search) {
      const search = filters.value.search.toLowerCase()
      result = result.filter(p => 
        p.name.toLowerCase().includes(search) ||
        p.description?.toLowerCase().includes(search)
      )
    }

    if (filters.value.category_id) {
      result = result.filter(p => p.category_id === filters.value.category_id)
    }

    if (filters.value.min_price) {
      result = result.filter(p => p.price >= filters.value.min_price)
    }

    if (filters.value.max_price) {
      result = result.filter(p => p.price <= filters.value.max_price)
    }

    // Sorting
    if (filters.value.sort === 'price_asc') {
      result.sort((a, b) => a.price - b.price)
    } else if (filters.value.sort === 'price_desc') {
      result.sort((a, b) => b.price - a.price)
    } else {
      result.sort((a, b) => a.name.localeCompare(b.name))
    }

    return result
  })

  async function fetchProducts() {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.get('/products')
      products.value = response.data || response
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch products'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchProduct(id) {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.get(`/products/${id}`)
      currentProduct.value = response.data || response
      return currentProduct.value
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch product'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function fetchCategories() {
    try {
      const response = await api.get('/categories')
      categories.value = response.data || response
    } catch (err) {
      console.error('Failed to fetch categories:', err)
    }
  }

  async function createProduct(productData) {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.post('/products', productData)
      await fetchProducts()
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to create product'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function updateProduct(id, productData) {
    loading.value = true
    error.value = null
    
    try {
      const response = await api.put(`/products/${id}`, productData)
      await fetchProducts()
      return response
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update product'
      throw err
    } finally {
      loading.value = false
    }
  }

  async function deleteProduct(id) {
    loading.value = true
    error.value = null
    
    try {
      await api.delete(`/products/${id}`)
      await fetchProducts()
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to delete product'
      throw err
    } finally {
      loading.value = false
    }
  }

  function setFilter(key, value) {
    filters.value[key] = value
  }

  function resetFilters() {
    filters.value = {
      search: '',
      category_id: null,
      min_price: null,
      max_price: null,
      sort: 'name'
    }
  }

  return {
    products,
    categories,
    currentProduct,
    loading,
    error,
    filters,
    filteredProducts,
    fetchProducts,
    fetchProduct,
    fetchCategories,
    createProduct,
    updateProduct,
    deleteProduct,
    setFilter,
    resetFilters
  }
})
