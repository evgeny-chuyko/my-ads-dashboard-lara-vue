import {defineStore} from 'pinia'
import {ref, computed} from 'vue'
import {authApi} from '@/api/auth'
import type {User, LoginCredentials, RegisterData} from '@/types'

export const useAuthStore = defineStore('auth', () => {
    const user = ref<User | null>(null)
    const token = ref<string | null>(localStorage.getItem('token'))
    const loading = ref(false)
    const error = ref<string | null>(null)

    const isAuthenticated = computed(() => !!token.value)
    const isAdmin = computed(() => user.value?.role.id === 1)
    const isPublisher = computed(() => user.value?.role.id === 2)

    async function login(credentials: LoginCredentials) {
        loading.value = true
        error.value = null
        try {
            const response = await authApi.login(credentials)
            user.value = response.user
            token.value = response.token
            localStorage.setItem('token', response.token)
            localStorage.setItem('user', JSON.stringify(response.user))
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Login failed'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function register(data: RegisterData) {
        loading.value = true
        error.value = null
        try {
            const response = await authApi.register(data)
            user.value = response.user
            token.value = response.token
            localStorage.setItem('token', response.token)
            localStorage.setItem('user', JSON.stringify(response.user))
        } catch (e: any) {
            error.value = e.response?.data?.message || 'Registration failed'
            throw e
        } finally {
            loading.value = false
        }
    }

    async function logout() {
        try {
            await authApi.logout()
        } catch (e) {
            console.error('Logout error:', e)
        } finally {
            user.value = null
            token.value = null
            localStorage.removeItem('token')
            localStorage.removeItem('user')
        }
    }

    async function fetchUser() {
        if (!token.value) return
        try {
            user.value = await authApi.me()
            localStorage.setItem('user', JSON.stringify(user.value))
        } catch (e) {
            console.error('Fetch user error:', e)
            logout()
        }
    }

    function initFromStorage() {
        const storedUser = localStorage.getItem('user')
        if (storedUser) {
            user.value = JSON.parse(storedUser)
        }
    }

    return {
        user,
        token,
        loading,
        error,
        isAuthenticated,
        isAdmin,
        isPublisher,
        login,
        register,
        logout,
        fetchUser,
        initFromStorage
    }
})
