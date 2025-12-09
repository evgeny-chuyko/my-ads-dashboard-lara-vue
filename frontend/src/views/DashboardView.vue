<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-gray-900">MyAds Dashboard</h1>
          </div>
          <div class="flex items-center space-x-4">
            <a
              href="http://localhost/api/documentation"
              target="_blank"
              class="flex items-center px-3 py-2 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition"
              title="API Documentation"
            >
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              API Docs
            </a>
            <span class="text-sm text-gray-700">{{ authStore.user?.name }}</span>
            <span class="px-3 py-1 text-xs font-medium rounded-full" :class="roleClass">
              {{ authStore.user?.role.name }}
            </span>
            <button
              @click="handleLogout"
              class="px-4 py-2 text-sm text-gray-700 hover:text-gray-900"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <router-link
          v-if="authStore.isPublisher"
          to="/apps"
          class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition"
        >
          <h2 class="text-xl font-semibold text-gray-900 mb-2">My Apps</h2>
          <p class="text-gray-600">Manage your applications</p>
        </router-link>

        <router-link
          v-if="authStore.isAdmin"
          to="/admin"
          class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition"
        >
          <h2 class="text-xl font-semibold text-gray-900 mb-2">Admin Panel</h2>
          <p class="text-gray-600">Manage users and applications</p>
        </router-link>

        <router-link
          v-if="authStore.isAdmin"
          to="/admin/users"
          class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition"
        >
          <h2 class="text-xl font-semibold text-gray-900 mb-2">Users</h2>
          <p class="text-gray-600">View and manage users</p>
        </router-link>

        <router-link
          v-if="authStore.isAdmin"
          to="/admin/apps"
          class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition"
        >
          <h2 class="text-xl font-semibold text-gray-900 mb-2">All Apps</h2>
          <p class="text-gray-600">View all applications</p>
        </router-link>

        <a
          href="http://localhost/api/documentation"
          target="_blank"
          class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-lg shadow hover:shadow-lg transition text-white"
        >
          <div class="flex items-center mb-2">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h2 class="text-xl font-semibold">API Documentation</h2>
          </div>
          <p class="text-blue-100">Swagger/OpenAPI docs - 16 endpoints</p>
          <div class="mt-3 flex items-center text-sm text-blue-100">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
            </svg>
            Open in new tab
          </div>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const roleClass = computed(() => {
  return authStore.isAdmin
    ? 'bg-red-100 text-red-800'
    : 'bg-blue-100 text-blue-800'
})

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>
