<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center space-x-4">
            <router-link to="/admin" class="text-gray-600 hover:text-gray-900">← Back</router-link>
            <h1 class="text-2xl font-bold text-gray-900">All Applications</h1>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="adminStore.loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="app in adminStore.apps"
          :key="app.id"
          class="bg-white rounded-lg shadow p-6"
        >
          <div class="flex justify-between items-start mb-4">
            <h3 class="text-lg font-semibold text-gray-900">{{ app.name }}</h3>
            <span
              class="px-2 py-1 text-xs font-medium rounded"
              :class="statusClass(app.status)"
            >
              {{ app.status }}
            </span>
          </div>
          
          <p class="text-gray-600 text-sm mb-4">{{ app.description || 'No description' }}</p>
          
          <div class="text-sm text-gray-500 space-y-1">
            <p><strong>Owner:</strong> {{ app.user?.name }}</p>
            <p><strong>Impressions:</strong> {{ app.impressions.toLocaleString() }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'

const adminStore = useAdminStore()

onMounted(() => {
  adminStore.fetchApps()
})

function statusClass(status: string) {
  const classes = {
    active: 'bg-green-100 text-green-800',
    paused: 'bg-yellow-100 text-yellow-800',
    archived: 'bg-gray-100 text-gray-800'
  }
  return classes[status as keyof typeof classes] || classes.active
}
</script>
