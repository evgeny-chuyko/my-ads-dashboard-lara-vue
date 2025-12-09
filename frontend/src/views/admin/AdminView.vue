<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center space-x-4">
            <router-link to="/" class="text-gray-600 hover:text-gray-900">← Back</router-link>
            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="adminStore.loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>

      <div v-else-if="adminStore.stats" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-sm font-medium text-gray-500 mb-2">Total Users</h3>
          <p class="text-3xl font-bold text-gray-900">{{ adminStore.stats.total_users }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-sm font-medium text-gray-500 mb-2">Total Apps</h3>
          <p class="text-3xl font-bold text-gray-900">{{ adminStore.stats.total_apps }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-sm font-medium text-gray-500 mb-2">Total Impressions</h3>
          <p class="text-3xl font-bold text-gray-900">{{ parseInt(adminStore.stats.total_impressions).toLocaleString() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-sm font-medium text-gray-500 mb-2">Publishers</h3>
          <p class="text-3xl font-bold text-gray-900">{{ adminStore.stats.total_publishers }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-sm font-medium text-gray-500 mb-2">Active Apps</h3>
          <p class="text-3xl font-bold text-gray-900">{{ adminStore.stats.active_apps }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <h3 class="text-sm font-medium text-gray-500 mb-2">Banned Users</h3>
          <p class="text-3xl font-bold text-gray-900">{{ adminStore.stats.banned_users }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <router-link
          to="/admin/users"
          class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition"
        >
          <h2 class="text-xl font-semibold text-gray-900 mb-2">Manage Users</h2>
          <p class="text-gray-600">View and manage all users</p>
        </router-link>

        <router-link
          to="/admin/apps"
          class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition"
        >
          <h2 class="text-xl font-semibold text-gray-900 mb-2">Manage Apps</h2>
          <p class="text-gray-600">View all applications</p>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useAdminStore } from '@/stores/admin'

const adminStore = useAdminStore()

onMounted(() => {
  adminStore.fetchStats()
})
</script>
