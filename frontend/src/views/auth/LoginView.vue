<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
      <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">MyAds Dashboard</h2>
      
      <form @submit.prevent="handleLogin" class="space-y-6">
        <div v-if="authStore.error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
          {{ authStore.error }}
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="admin@myads.com"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="••••••••"
          />
        </div>

        <button
          type="submit"
          :disabled="authStore.loading"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
          {{ authStore.loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-gray-600">
        Don't have an account?
        <router-link to="/register" class="text-blue-600 hover:text-blue-700 font-medium">
          Register
        </router-link>
      </p>

      <div class="mt-6 pt-6 border-t border-gray-200">
        <p class="text-xs text-gray-500 text-center mb-2">Test Accounts:</p>
        <div class="text-xs text-gray-600 space-y-1">
          <p><strong>Admin:</strong> admin@myads.com / password</p>
          <p><strong>Publisher:</strong> publisher@myads.com / password</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: ''
})

async function handleLogin() {
  try {
    await authStore.login(form.value)
    router.push('/')
  } catch (error) {
    console.error('Login error:', error)
  }
}
</script>
