<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center space-x-4">
            <router-link to="/" class="text-gray-600 hover:text-gray-900">← Back</router-link>
            <h1 class="text-2xl font-bold text-gray-900">My Apps</h1>
          </div>
          <div class="flex items-center">
            <button
              @click="showCreateModal = true"
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
              + New App
            </button>
          </div>
        </div>
      </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div v-if="appsStore.loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>

      <div v-else-if="appsStore.apps.length === 0" class="text-center py-12">
        <p class="text-gray-500">No apps yet. Create your first app!</p>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="app in appsStore.apps"
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
          
          <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
            <span>{{ app.impressions.toLocaleString() }} impressions</span>
          </div>

          <div class="flex space-x-2">
            <button
              @click="editApp(app)"
              class="flex-1 px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200"
            >
              Edit
            </button>
            <button
              @click="deleteAppConfirm(app)"
              class="flex-1 px-3 py-2 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h2 class="text-xl font-bold mb-4">Create New App</h2>
        <form @submit.prevent="handleCreate">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
              <input
                v-model="createForm.name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
              <textarea
                v-model="createForm.description"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              ></textarea>
            </div>
          </div>
          <div class="flex space-x-3 mt-6">
            <button
              type="button"
              @click="showCreateModal = false"
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
              Create
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Modal -->
    <div v-if="showEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h2 class="text-xl font-bold mb-4">Edit App</h2>
        <form @submit.prevent="handleUpdate">
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
              <input
                v-model="editForm.name"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
              <textarea
                v-model="editForm.description"
                rows="3"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              ></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
              <select
                v-model="editForm.status"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
              >
                <option value="active">Active</option>
                <option value="paused">Paused</option>
                <option value="archived">Archived</option>
              </select>
            </div>
          </div>
          <div class="flex space-x-3 mt-6">
            <button
              type="button"
              @click="showEditModal = false"
              class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            >
              Update
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAppsStore } from '@/stores/apps'
import type { App } from '@/types'

const appsStore = useAppsStore()

const showCreateModal = ref(false)
const showEditModal = ref(false)
const createForm = ref({ name: '', description: '' })
const editForm = ref({ id: 0, name: '', description: '', status: 'active' })

onMounted(() => {
  appsStore.fetchApps()
})

function statusClass(status: string) {
  const classes = {
    active: 'bg-green-100 text-green-800',
    paused: 'bg-yellow-100 text-yellow-800',
    archived: 'bg-gray-100 text-gray-800'
  }
  return classes[status as keyof typeof classes] || classes.active
}

async function handleCreate() {
  try {
    await appsStore.createApp(createForm.value)
    showCreateModal.value = false
    createForm.value = { name: '', description: '' }
  } catch (error) {
    console.error('Create error:', error)
  }
}

function editApp(app: App) {
  editForm.value = {
    id: app.id,
    name: app.name,
    description: app.description || '',
    status: app.status
  }
  showEditModal.value = true
}

async function handleUpdate() {
  try {
    await appsStore.updateApp(editForm.value.id, {
      name: editForm.value.name,
      description: editForm.value.description,
      status: editForm.value.status
    })
    showEditModal.value = false
  } catch (error) {
    console.error('Update error:', error)
  }
}

async function deleteAppConfirm(app: App) {
  if (confirm(`Are you sure you want to delete "${app.name}"?`)) {
    try {
      await appsStore.deleteApp(app.id)
    } catch (error) {
      console.error('Delete error:', error)
    }
  }
}
</script>
