<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-500 to-purple-600 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
      <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Create Account</h2>
      
      <form @submit.prevent="handleRegister" class="space-y-6">
        <div v-if="authStore.error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
          {{ authStore.error }}
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
          <input
            v-model="form.name"
            type="text"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="John Doe"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
          <input
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="john@example.com"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
          <input
            v-model="form.password"
            type="password"
            required
            minlength="8"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="••••••••"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
          <input
            v-model="form.password_confirmation"
            type="password"
            required
            minlength="8"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="••••••••"
          />
        </div>

        <button
          type="submit"
          :disabled="authStore.loading"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
          {{ authStore.loading ? 'Creating account...' : 'Register' }}
        </button>
      </form>

      <p class="mt-6 text-center text-sm text-gray-600">
        Already have an account?
        <router-link to="/login" class="text-blue-600 hover:text-blue-700 font-medium">
          Login
        </router-link>
      </p>
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
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

async function handleRegister() {
  try {
    await authStore.register(form.value)
    router.push('/')
  } catch (error) {
    console.error('Register error:', error)
  }
}
</script>
