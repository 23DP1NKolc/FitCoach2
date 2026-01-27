import { createRouter, createWebHistory } from 'vue-router'

import Home from '../pages/Home.vue'
import Trainers from '../pages/Trainers.vue'
import TrainerProfile from '../pages/TrainerProfile.vue'

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/treneri', name: 'trainers', component: Trainers },
  { path: '/treneri/:id', name: 'trainer', component: TrainerProfile },
]

export default createRouter({
  history: createWebHistory(),
  routes,
})