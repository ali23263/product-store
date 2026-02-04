<script setup>
import { ref, onMounted } from 'vue'
import api from '@/services/api'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import AlertMessage from '@/components/common/AlertMessage.vue'

const users = ref([])
const loading = ref(false)
const error = ref('')
const success = ref('')

const fetchUsers = async () => {
  loading.value = true
  try {
    const response = await api.get('/admin/users')
    users.value = response.data || response
  } catch (err) {
    error.value = 'Failed to fetch users'
  } finally {
    loading.value = false
  }
}

onMounted(fetchUsers)

const updateUserRole = async (userId, newRole) => {
  try {
    await api.patch(`/admin/users/${userId}`, { role: newRole })
    success.value = 'User role updated successfully'
    await fetchUsers()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to update user role'
  }
}

const deleteUser = async (userId) => {
  if (!confirm('Are you sure you want to delete this user?')) return
  
  try {
    await api.delete(`/admin/users/${userId}`)
    success.value = 'User deleted successfully'
    await fetchUsers()
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to delete user'
  }
}

const roleColors = {
  customer: 'bg-blue-100 text-blue-800',
  admin: 'bg-red-100 text-red-800',
  picker: 'bg-green-100 text-green-800'
}
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold mb-8">Users</h1>

    <AlertMessage 
      v-if="error" 
      type="error" 
      :message="error"
      dismissible
      @dismiss="error = ''"
      class="mb-4"
    />

    <AlertMessage 
      v-if="success" 
      type="success" 
      :message="success"
      dismissible
      @dismiss="success = ''"
      class="mb-4"
    />

    <LoadingSpinner v-if="loading" />

    <div v-else class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="users.length === 0" class="text-center py-8 text-gray-600">
        No users found
      </div>

      <table v-else class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="user in users" :key="user.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ user.id }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <select 
                :value="user.role"
                @change="updateUserRole(user.id, $event.target.value)"
                :class="[roleColors[user.role], 'px-2 py-1 text-xs rounded-full border-0']"
              >
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
                <option value="picker">Picker</option>
              </select>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">{{ new Date(user.created_at).toLocaleDateString() }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              <button 
                @click="deleteUser(user.id)"
                class="text-red-600 hover:text-red-800"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
