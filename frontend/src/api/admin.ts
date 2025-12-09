import apiClient from './client'
import type { User, App, AdminStats } from '@/types'

export const adminApi = {
  async getUsers(): Promise<User[]> {
    const { data } = await apiClient.get('/admin/users')
    return data.data
  },

  async getApps(): Promise<App[]> {
    const { data } = await apiClient.get('/admin/apps')
    return data.data
  },

  async getStats(): Promise<AdminStats> {
    const { data } = await apiClient.get('/admin/stats')
    return data
  },

  async banUser(id: number): Promise<User> {
    const { data } = await apiClient.post(`/admin/users/${id}/ban`)
    return data.data
  },

  async unbanUser(id: number): Promise<User> {
    const { data } = await apiClient.post(`/admin/users/${id}/unban`)
    return data.data
  }
}
