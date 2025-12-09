import {defineStore} from 'pinia'
import {ref} from 'vue'
import {appsApi} from '@/api/apps'
import type {App, Stats} from '@/types'

export const useAppsStore = defineStore('apps', () => {
    const apps = ref<App[]>([])
    const currentApp = ref<App | null>(null)
    const currentStats = ref<Stats | null>(null)
    const loading = ref(false)
    const error = ref<string | null>(null)

    async function fetchApps() {
        loading.value = true
        error.value = null
        try {
            apps.value = await appsApi.getAll()
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to fetch apps'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function createApp(data: { name: string; description?: string }) {
        loading.value = true
        error.value = null
        try {
            const newApp = await appsApi.create(data)
            apps.value.unshift(newApp)
            return newApp
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to create app'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function updateApp(id: number, data: Partial<App>) {
        loading.value = true
        error.value = null
        try {
            const updatedApp = await appsApi.update(id, data)
            const index = apps.value.findIndex(app => app.id === id)
            if (index !== -1) {
                apps.value[index] = updatedApp
            }
            return updatedApp
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to update app'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function deleteApp(id: number) {
        loading.value = true
        error.value = null
        try {
            await appsApi.delete(id)
            apps.value = apps.value.filter(app => app.id !== id)
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to delete app'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function fetchStats(id: number) {
        loading.value = true
        error.value = null
        try {
            currentStats.value = await appsApi.getStats(id)
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to fetch stats'
            throw e
        } finally {
            loading.value = false
        }
    }

    return {
        apps,
        currentApp,
        currentStats,
        loading,
        error,
        fetchApps,
        createApp,
        updateApp,
        deleteApp,
        fetchStats
    }
})
