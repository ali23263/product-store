import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Layouts
import MainLayout from '@/layouts/MainLayout.vue'
import AuthLayout from '@/layouts/AuthLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

// Pages
import Home from '@/pages/Home.vue'
import Login from '@/pages/Login.vue'
import Register from '@/pages/Register.vue'
import ProductDetail from '@/pages/ProductDetail.vue'
import Cart from '@/pages/Cart.vue'
import Dashboard from '@/pages/Dashboard.vue'
import Admin from '@/pages/Admin.vue'
import Picker from '@/pages/Picker.vue'

const routes = [
  {
    path: '/login',
    component: AuthLayout,
    children: [
      {
        path: '',
        name: 'Login',
        component: Login,
        meta: { guest: true },
      },
    ],
  },
  {
    path: '/register',
    component: AuthLayout,
    children: [
      {
        path: '',
        name: 'Register',
        component: Register,
        meta: { guest: true },
      },
    ],
  },
  {
    path: '/',
    component: MainLayout,
    children: [
      {
        path: '',
        name: 'Home',
        component: Home,
      },
      {
        path: 'product/:id',
        name: 'ProductDetail',
        component: ProductDetail,
      },
      {
        path: 'cart',
        name: 'Cart',
        component: Cart,
      },
      {
        path: 'dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true },
      },
    ],
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, role: 'admin' },
    children: [
      {
        path: '',
        name: 'Admin',
        component: Admin,
      },
    ],
  },
  {
    path: '/picker',
    component: AdminLayout,
    meta: { requiresAuth: true, role: 'picker' },
    children: [
      {
        path: '',
        name: 'Picker',
        component: Picker,
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

// Navigation guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  // Check if route requires authentication
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!authStore.isAuthenticated) {
      next({ name: 'Login', query: { redirect: to.fullPath } })
      return
    }
    
    // Check role-based access
    const requiredRole = to.matched.find(record => record.meta.role)?.meta.role
    if (requiredRole) {
      if (requiredRole === 'admin' && !authStore.isAdmin) {
        next({ name: 'Home' })
        return
      }
      if (requiredRole === 'picker' && !authStore.isPicker) {
        next({ name: 'Home' })
        return
      }
      if (requiredRole === 'customer' && !authStore.isCustomer) {
        next({ name: 'Home' })
        return
      }
    }
  }
  
  // Redirect authenticated users away from guest pages
  if (to.matched.some(record => record.meta.guest)) {
    if (authStore.isAuthenticated) {
      next({ name: 'Home' })
      return
    }
  }
  
  next()
})

export default router
