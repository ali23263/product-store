import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Layouts
import MainLayout from '@/components/layout/MainLayout.vue'

// Public pages
import HomePage from '@/views/HomePage.vue'
import ProductsPage from '@/views/ProductsPage.vue'
import ProductDetailPage from '@/views/ProductDetailPage.vue'
import LoginPage from '@/views/LoginPage.vue'
import RegisterPage from '@/views/RegisterPage.vue'

// Customer pages
import CartPage from '@/views/CartPage.vue'
import CheckoutPage from '@/views/CheckoutPage.vue'
import DashboardPage from '@/views/DashboardPage.vue'
import OrderDetailPage from '@/views/OrderDetailPage.vue'

// Admin pages
import AdminDashboardPage from '@/views/admin/DashboardPage.vue'
import AdminProductsPage from '@/views/admin/ProductsPage.vue'
import AdminProductFormPage from '@/views/admin/ProductFormPage.vue'
import AdminCategoriesPage from '@/views/admin/CategoriesPage.vue'
import AdminOrdersPage from '@/views/admin/OrdersPage.vue'
import AdminOrderDetailPage from '@/views/admin/OrderDetailPage.vue'
import AdminPromoCodesPage from '@/views/admin/PromoCodesPage.vue'
import AdminUsersPage from '@/views/admin/UsersPage.vue'

// Picker pages
import PickerOrdersPage from '@/views/picker/OrdersPage.vue'
import PickerOrderDetailPage from '@/views/picker/OrderDetailPage.vue'

const routes = [
  {
    path: '/',
    component: MainLayout,
    children: [
      {
        path: '',
        name: 'home',
        component: HomePage
      },
      {
        path: 'products',
        name: 'products',
        component: ProductsPage
      },
      {
        path: 'products/:id',
        name: 'product-detail',
        component: ProductDetailPage
      },
      {
        path: 'login',
        name: 'login',
        component: LoginPage,
        meta: { guest: true }
      },
      {
        path: 'register',
        name: 'register',
        component: RegisterPage,
        meta: { guest: true }
      },
      {
        path: 'cart',
        name: 'cart',
        component: CartPage
      },
      {
        path: 'checkout',
        name: 'checkout',
        component: CheckoutPage,
        meta: { requiresAuth: true }
      },
      {
        path: 'dashboard',
        name: 'dashboard',
        component: DashboardPage,
        meta: { requiresAuth: true }
      },
      {
        path: 'orders/:id',
        name: 'order-detail',
        component: OrderDetailPage,
        meta: { requiresAuth: true }
      },
      // Admin routes
      {
        path: 'admin',
        redirect: '/admin/dashboard',
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'admin/dashboard',
        name: 'admin-dashboard',
        component: AdminDashboardPage,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'admin/products',
        name: 'admin-products',
        component: AdminProductsPage,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'admin/products/create',
        name: 'admin-product-create',
        component: AdminProductFormPage,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'admin/products/:id/edit',
        name: 'admin-product-edit',
        component: AdminProductFormPage,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'admin/categories',
        name: 'admin-categories',
        component: AdminCategoriesPage,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'admin/orders',
        name: 'admin-orders',
        component: AdminOrdersPage,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'admin/orders/:id',
        name: 'admin-order-detail',
        component: AdminOrderDetailPage,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'admin/promo-codes',
        name: 'admin-promo-codes',
        component: AdminPromoCodesPage,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      {
        path: 'admin/users',
        name: 'admin-users',
        component: AdminUsersPage,
        meta: { requiresAuth: true, requiresAdmin: true }
      },
      // Picker routes
      {
        path: 'picker/orders',
        name: 'picker-orders',
        component: PickerOrdersPage,
        meta: { requiresAuth: true, requiresPicker: true }
      },
      {
        path: 'picker/orders/:id',
        name: 'picker-order-detail',
        component: PickerOrderDetailPage,
        meta: { requiresAuth: true, requiresPicker: true }
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navigation guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  // Check if route requires authentication
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login', query: { redirect: to.fullPath } })
    return
  }

  // Check if route is for guests only
  if (to.meta.guest && authStore.isAuthenticated) {
    next({ name: 'home' })
    return
  }

  // Check admin access
  if (to.meta.requiresAdmin && !authStore.isAdmin) {
    next({ name: 'home' })
    return
  }

  // Check picker access
  if (to.meta.requiresPicker && !authStore.isPicker) {
    next({ name: 'home' })
    return
  }

  next()
})

export default router
