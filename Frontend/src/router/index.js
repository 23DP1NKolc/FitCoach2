import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import Trainers from '../pages/Trainers.vue'
import TrainerProfile from '../pages/TrainerProfile.vue'
import Login from '../pages/Login.vue'
import MyBookings from '../pages/MyBookings.vue'
import Register from '../pages/Register.vue'
import Statistika from '../pages/Statistika.vue'
import AdminLayout from '../pages/admin/AdminLayout.vue'

const routes = [
  { path: '/manas-rezervacijas', component: MyBookings },
  { path: '/', component: Home },
 { path: '/treneri', component: Trainers, meta: { requiresAuth: true } },
{ path: '/treneri/:id', component: TrainerProfile, meta: { requiresAuth: true } },
{ path: '/manas-rezervacijas', component: MyBookings, meta: { requiresAuth: true } },
{ path: '/admin', component: AdminLayout, meta: { requiresAuth: true, requiresAdmin: true } },
  { path: '/statistika', component: Statistika },

  { path: '/login', component: Login },
  { path: '/register', component: Register },

  { path: '/admin', component: AdminLayout, meta: { requiresAdmin: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const token = localStorage.getItem('token')
  const user = (() => {
    try { return JSON.parse(localStorage.getItem('user') || 'null') } catch { return null }
  })()

  if (to.meta.requiresAuth && !token) {
    return { path: '/login', query: { redirect: to.fullPath } }
  }

  if (to.meta.requiresAdmin && user?.role !== 'admin') {
    return { path: '/' }
  }
})

export default router