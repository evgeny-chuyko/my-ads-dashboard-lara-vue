<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center space-x-4">
            <router-link to="/admin" class="text-gray-600 hover:text-gray-900">← Back</router-link>
            <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="adminStore.loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>

      <div v-else class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in adminStore.users" :key="user.id">
              <td class="px-6 py-4">
                <div>
                  <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                  <div class="text-sm text-gray-500">{{ user.email }}</div>
                </div>
              </td>
              <td class="px-6 py-4">
                <span
                  class="px-2 py-1 text-xs font-medium rounded"
                  :class="user.role.id === 1 ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'"
                >
                  {{ user.role.name }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span
                  class="px-2 py-1 text-xs font-medium rounded"
                  :class="user.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                >
                  {{ user.status }}
                </span>
              </td>
              <td class="px-6 py-4">
                <button
                  v-if="user.status === 'active' && user.role.id !== 1"
                  @click="handleBan(user.id)"
                  class="text-sm text-red-600 hover:text-red-900"
                >
                  Ban
                </button>
                <button
                  v-if="user.status === 'banned'"
                  @click="handleUnban(user.id)"
                  class="text-sm text-green-600 hover:text-green-900"
                >
                  Unban
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'

const adminStore = useAdminStore()

onMounted(() => {
  adminStore.fetchUsers()
})

async function handleBan(id: number) {
  if (confirm('Are you sure you want to ban this user?')) {
    try {
      await adminStore.banUser(id)
    } catch (error) {
      console.error('Ban error:', error)
    }
  }
}

async function handleUnban(id: number) {
  try {
    await adminStore.unbanUser(id)
  } catch (error) {
    console.error('Unban error:', error)
  }
}
</script>
