import apiClient from './client'
import type { LoginCredentials, RegisterData, AuthResponse, User } from '@/types'

export const authApi = {
  async login(credentials: LoginCredentials): Promise<AuthResponse> {
    const { data } = await apiClient.post('/auth/login', credentials)
    return data
  },

  async register(userData: RegisterData): Promise<AuthResponse> {
    const { data } = await apiClient.post('/auth/register', userData)
    return data
  },

  async logout(): Promise<void> {
    await apiClient.post('/auth/logout')
  },

  async me(): Promise<User> {
    const { data } = await apiClient.get('/auth/me')
    return data
  }
}
