<template>
  <div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b">
      <h2 class="text-xl font-bold text-gray-900">Users Management</h2>
    </div>

    <div class="p-6">
      <div v-if="loading" class="flex justify-center py-8">
        <LoadingSpinner />
      </div>
      
      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ user.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">{{ user.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">{{ user.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="roleClasses[user.role]" class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">{{ formatDate(user.created_at) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { usersAPI } from '@/services/api'
import { formatDate } from '@/utils/helpers'
import { useToast } from '@/utils/toast'
import LoadingSpinner from '@/components/LoadingSpinner.vue'

const { showToast } = useToast()
const users = ref([])
const loading = ref(false)

const roleClasses = {
  admin: 'bg-purple-100 text-purple-800',
  picker: 'bg-blue-100 text-blue-800',
  customer: 'bg-green-100 text-green-800',
}

onMounted(async () => {
  loading.value = true
  try {
    const response = await usersAPI.getAll()
    users.value = response.data.data || response.data
  } catch (error) {
    showToast('Failed to load users', 'error')
  } finally {
    loading.value = false
  }
})
</script>
