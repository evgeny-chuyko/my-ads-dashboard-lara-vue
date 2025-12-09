import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/login',
      name: 'Login',
      component: () => import('@/views/auth/LoginView.vue'),
      meta: { guest: true }
    },
    {
      path: '/register',
      name: 'Register',
      component: () => import('@/views/auth/RegisterView.vue'),
      meta: { guest: true }
    },
    {
      path: '/',
      name: 'Dashboard',
      component: () => import('@/views/DashboardView.vue'),
      meta: { requiresAuth: true }
    },
    {
      path: '/apps',
      name: 'Apps',
      component: () => import('@/views/publisher/AppsView.vue'),
      meta: { requiresAuth: true, role: 'publisher' }
    },
    {
      path: '/admin',
      name: 'Admin',
      component: () => import('@/views/admin/AdminView.vue'),
      meta: { requiresAuth: true, role: 'admin' }
    },
    {
      path: '/admin/users',
      name: 'AdminUsers',
      component: () => import('@/views/admin/UsersView.vue'),
      meta: { requiresAuth: true, role: 'admin' }
    },
    {
      path: '/admin/apps',
      name: 'AdminApps',
      component: () => import('@/views/admin/AppsView.vue'),
      meta: { requiresAuth: true, role: 'admin' }
    }
  ]
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  authStore.initFromStorage()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next('/')
  } else if (to.meta.role === 'admin' && !authStore.isAdmin) {
    next('/')
  } else if (to.meta.role === 'publisher' && !authStore.isPublisher) {
    next('/')
  } else {
    next()
  }
})

export default router
