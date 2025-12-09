export interface User {
  id: number
  name: string
  email: string
  role: {
    id: number
    name: string
  }
  status: string
  created_at: string
}

export interface App {
  id: number
  name: string
  description: string | null
  status: string
  impressions: number
  user?: {
    id: number
    name: string
    email: string
  }
  created_at: string
  updated_at: string
}

export interface Stats {
  total_impressions: number
  status: string
  created_at: string
  days_active: number
  avg_daily_impressions: number
}

export interface AdminStats {
  total_users: number
  total_publishers: number
  total_apps: number
  active_apps: number
  total_impressions: string
  banned_users: number
}

export interface LoginCredentials {
  email: string
  password: string
}

export interface RegisterData {
  name: string
  email: string
  password: string
  password_confirmation: string
}

export interface AuthResponse {
  user: User
  token: string
}
