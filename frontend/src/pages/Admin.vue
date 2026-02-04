<template>
  <div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Admin Dashboard</h1>

    <!-- Tabs -->
    <div class="mb-8">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'py-4 px-1 border-b-2 font-medium text-sm transition-colors',
              activeTab === tab.id
                ? 'border-primary-500 text-primary-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            {{ tab.name }}
          </button>
        </nav>
      </div>
    </div>

    <!-- Dashboard Tab -->
    <div v-show="activeTab === 'dashboard'">
      <DashboardStats />
    </div>

    <!-- Products Tab -->
    <div v-show="activeTab === 'products'">
      <ProductsManagement />
    </div>

    <!-- Categories Tab -->
    <div v-show="activeTab === 'categories'">
      <CategoriesManagement />
    </div>

    <!-- Orders Tab -->
    <div v-show="activeTab === 'orders'">
      <OrdersManagement />
    </div>

    <!-- Promo Codes Tab -->
    <div v-show="activeTab === 'promos'">
      <PromoCodesManagement />
    </div>

    <!-- Users Tab -->
    <div v-show="activeTab === 'users'">
      <UsersManagement />
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import DashboardStats from '@/components/admin/DashboardStats.vue'
import ProductsManagement from '@/components/admin/ProductsManagement.vue'
import CategoriesManagement from '@/components/admin/CategoriesManagement.vue'
import OrdersManagement from '@/components/admin/OrdersManagement.vue'
import PromoCodesManagement from '@/components/admin/PromoCodesManagement.vue'
import UsersManagement from '@/components/admin/UsersManagement.vue'

const activeTab = ref('dashboard')

const tabs = [
  { id: 'dashboard', name: 'Dashboard' },
  { id: 'products', name: 'Products' },
  { id: 'categories', name: 'Categories' },
  { id: 'orders', name: 'Orders' },
  { id: 'promos', name: 'Promo Codes' },
  { id: 'users', name: 'Users' },
]
</script>
