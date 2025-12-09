import {defineStore} from 'pinia'
import {ref} from 'vue'
import {adminApi} from '@/api/admin'
import type {User, App, AdminStats} from '@/types'

export const useAdminStore = defineStore('admin', () => {
    const users = ref<User[]>([])
    const apps = ref<App[]>([])
    const stats = ref<AdminStats | null>(null)
    const loading = ref(false)
    const error = ref<string | null>(null)

    async function fetchUsers() {
        loading.value = true
        error.value = null
        try {
            users.value = await adminApi.getUsers()
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to fetch users'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function fetchApps() {
        loading.value = true
        error.value = null
        try {
            apps.value = await adminApi.getApps()
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to fetch apps'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function fetchStats() {
        loading.value = true
        error.value = null
        try {
            stats.value = await adminApi.getStats()
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to fetch stats'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function banUser(id: number) {
        loading.value = true
        error.value = null
        try {
            const updatedUser = await adminApi.banUser(id)
            const index = users.value.findIndex(u => u.id === id)
            if (index !== -1) {
                users.value[index] = updatedUser
            }
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to ban user'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function unbanUser(id: number) {
        loading.value = true
        error.value = null
        try {
            const updatedUser = await adminApi.unbanUser(id)
            const index = users.value.findIndex(u => u.id === id)
            if (index !== -1) {
                users.value[index] = updatedUser
            }
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Failed to unban user'
            throw e
        } finally {
            loading.value = false
        }
    }

    return {
        users,
        apps,
        stats,
        loading,
        error,
        fetchUsers,
        fetchApps,
        fetchStats,
        banUser,
        unbanUser
    }
})
