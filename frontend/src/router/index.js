import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    component: () => import('../layouts/PublicLayout.vue'),
    children: [
      { path: '', name: 'home', component: () => import('../views/Home.vue') },
      { path: 'login', name: 'login', component: () => import('../views/Login.vue') },
      { path: 'register', name: 'register', component: () => import('../views/Register.vue') },
      { path: 'planos', name: 'planos', component: () => import('../views/Planos.vue') },
      { path: 'docs', name: 'docs', component: () => import('../views/Docs.vue') },
    ],
  },
  {
    path: '/dashboard',
    component: () => import('../layouts/DashboardLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      { path: '', name: 'dashboard', component: () => import('../views/dashboard/Dashboard.vue') },
      { path: 'account', name: 'dashboard-account', component: () => import('../views/dashboard/Account.vue') },
      { path: 'subscription', name: 'dashboard-subscription', component: () => import('../views/dashboard/Subscription.vue') },
      { path: 'api-keys', name: 'dashboard-api-keys', component: () => import('../views/dashboard/ApiKeys.vue') },
      { path: 'payments', name: 'dashboard-payments', component: () => import('../views/dashboard/Payments.vue') },
      { path: 'usage', name: 'dashboard-usage', component: () => import('../views/dashboard/Usage.vue') },
      { path: 'settings', name: 'dashboard-settings', component: () => import('../views/dashboard/Settings.vue') },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) return { el: to.hash, behavior: 'smooth' }
    if (savedPosition) return savedPosition
    return { top: 0 }
  },
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('angoapi_token')
  if (to.matched.some((r) => r.meta.requiresAuth) && !token) {
    next({ name: 'login', query: { redirect: to.fullPath } })
  } else {
    next()
  }
})

export default router
