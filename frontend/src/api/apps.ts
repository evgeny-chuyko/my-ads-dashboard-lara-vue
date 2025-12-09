import apiClient from './client'
import type { App, Stats } from '@/types'

export const appsApi = {
  async getAll(): Promise<App[]> {
    const { data } = await apiClient.get('/apps')
    return data.data
  },

  async create(appData: { name: string; description?: string }): Promise<App> {
    const { data } = await apiClient.post('/apps', appData)
    return data.data
  },

  async update(id: number, appData: Partial<App>): Promise<App> {
    const { data } = await apiClient.put(`/apps/${id}`, appData)
    return data.data
  },

  async delete(id: number): Promise<void> {
    await apiClient.delete(`/apps/${id}`)
  },

  async getStats(id: number): Promise<Stats> {
    const { data } = await apiClient.get(`/apps/${id}/stats`)
    return data
  }
}
