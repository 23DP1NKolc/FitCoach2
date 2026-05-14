<template>
  <v-container class="py-8">
    <div class="d-flex align-center justify-space-between mb-5">
      <h1 class="text-h5 font-weight-black">Admin panelis</h1>
      <v-btn variant="tonal" @click="logout">Iziet</v-btn>
    </div>

    <v-tabs v-model="tab" color="primary">
      <v-tab value="sports">Sporti</v-tab>
      <v-tab value="trainers">Treneri</v-tab>
      <v-tab value="bookings">Rezervācijas</v-tab>
    </v-tabs>

    <v-window v-model="tab" class="mt-4">
      <v-window-item value="sports"><AdminSports /></v-window-item>
      <v-window-item value="trainers"><AdminTrainers /></v-window-item>
      <v-window-item value="bookings"><AdminBookings /></v-window-item>
    </v-window>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import api from '../../services/api'
import AdminSports from './AdminSports.vue'
import AdminTrainers from './AdminTrainers.vue'
import AdminBookings from './AdminBookings.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const tab = ref('sports')

async function logout() {
  try { await api.post('/auth/logout') } catch {}
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  router.push('/login')
}
</script>