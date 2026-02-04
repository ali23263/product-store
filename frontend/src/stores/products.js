import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { productsAPI, categoriesAPI } from '@/services/api'
import { useToast } from '@/utils/toast'

export const useProductsStore = defineStore('products', () => {
  const products = ref([])
  const categories = ref([])
  const currentProduct = ref(null)
  const loading = ref(false)
  const pagination = ref({
    currentPage: 1,
    perPage: 12,
    total: 0,
    lastPage: 1,
  })
  const filters = ref({
    search: '',
    category: null,
    minPrice: null,
    maxPrice: null,
  })

  const { showToast } = useToast()

  const filteredProducts = computed(() => {
    let result = [...products.value]

    if (filters.value.search) {
      const search = filters.value.search.toLowerCase()
      result = result.filter(p => 
        p.name.toLowerCase().includes(search) ||
        p.description?.toLowerCase().includes(search)
      )
    }

    if (filters.value.category) {
      result = result.filter(p => p.category_id === filters.value.category)
    }

    if (filters.value.minPrice !== null) {
      result = result.filter(p => p.price >= filters.value.minPrice)
    }

    if (filters.value.maxPrice !== null) {
      result = result.filter(p => p.price <= filters.value.maxPrice)
    }

    return result
  })

  const fetchProducts = async (params = {}) => {
    try {
      loading.value = true
      const response = await productsAPI.getAll({
        page: pagination.value.currentPage,
        per_page: pagination.value.perPage,
        ...params,
      })
      
      products.value = response.data.data || response.data
      
      if (response.data.meta) {
        pagination.value = {
          currentPage: response.data.meta.current_page,
          perPage: response.data.meta.per_page,
          total: response.data.meta.total,
          lastPage: response.data.meta.last_page,
        }
      }
    } catch (error) {
      showToast('Failed to load products', 'error')
      console.error('Fetch products error:', error)
    } finally {
      loading.value = false
    }
  }

  const fetchProductById = async (id) => {
    try {
      loading.value = true
      const response = await productsAPI.getById(id)
      currentProduct.value = response.data
      return response.data
    } catch (error) {
      showToast('Failed to load product details', 'error')
      console.error('Fetch product error:', error)
      throw error
    } finally {
      loading.value = false
    }
  }

  const fetchCategories = async () => {
    try {
      const response = await categoriesAPI.getAll()
      categories.value = response.data
    } catch (error) {
      console.error('Fetch categories error:', error)
    }
  }

  const createProduct = async (data) => {
    try {
      loading.value = true
      const response = await productsAPI.create(data)
      products.value.unshift(response.data)
      showToast('Product created successfully', 'success')
      return response.data
    } catch (error) {
      showToast('Failed to create product', 'error')
      throw error
    } finally {
      loading.value = false
    }
  }

  const updateProduct = async (id, data) => {
    try {
      loading.value = true
      const response = await productsAPI.update(id, data)
      const index = products.value.findIndex(p => p.id === id)
      if (index !== -1) {
        products.value[index] = response.data
      }
      showToast('Product updated successfully', 'success')
      return response.data
    } catch (error) {
      showToast('Failed to update product', 'error')
      throw error
    } finally {
      loading.value = false
    }
  }

  const deleteProduct = async (id) => {
    try {
      loading.value = true
      await productsAPI.delete(id)
      products.value = products.value.filter(p => p.id !== id)
      showToast('Product deleted successfully', 'success')
    } catch (error) {
      showToast('Failed to delete product', 'error')
      throw error
    } finally {
      loading.value = false
    }
  }

  const setFilter = (key, value) => {
    filters.value[key] = value
  }

  const clearFilters = () => {
    filters.value = {
      search: '',
      category: null,
      minPrice: null,
      maxPrice: null,
    }
  }

  return {
    products,
    categories,
    currentProduct,
    loading,
    pagination,
    filters,
    filteredProducts,
    fetchProducts,
    fetchProductById,
    fetchCategories,
    createProduct,
    updateProduct,
    deleteProduct,
    setFilter,
    clearFilters,
  }
})
