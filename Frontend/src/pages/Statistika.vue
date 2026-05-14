<template>
  <v-container class="py-8">
    <div class="d-flex align-center justify-space-between flex-wrap mb-6">
      <div>
        <h1 class="text-h4 font-weight-black mb-1">Statistika</h1>
        
      </div>

      <v-btn variant="tonal" color="primary" prepend-icon="mdi-refresh" @click="load">
        Atjaunot
      </v-btn>
    </div>

    <v-alert v-if="error" type="error" variant="tonal" class="mb-4" :text="error" />

    <v-row v-if="loading">
      <v-col cols="12" md="6"><v-skeleton-loader type="table" /></v-col>
      <v-col cols="12" md="6"><v-skeleton-loader type="table" /></v-col>
    </v-row>

    <v-row v-else>
      <v-col cols="12" md="6">
        <v-card class="pa-4" elevation="0" style="border-radius:18px;">
          <h2 class="text-h6 font-weight-bold mb-3">Treneri pa sportiem</h2>

          <v-table>
            <thead>
              <tr>
                <th>Sports</th>
                <th class="text-right">Treneru skaits</th>
                <th class="text-right">Vidējā cena</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="r in trainersBySport" :key="r.sport">
                <td>{{ r.sport }}</td>
                <td class="text-right">{{ r.treneru_skaits }}</td>
                <td class="text-right">
                  {{ r.videja_cena ? Number(r.videja_cena).toFixed(2) + ' €' : '-' }}
                </td>
              </tr>
            </tbody>
          </v-table>
        </v-card>
      </v-col>

      <v-col cols="12" md="6">
        <v-card class="pa-4" elevation="0" style="border-radius:18px;">
          <h2 class="text-h6 font-weight-bold mb-3">Rezervācijas pa dienām (30d)</h2>

          <v-table>
            <thead>
              <tr>
                <th>Diena</th>
                <th class="text-right">Rezervāciju skaits</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="r in bookingsByDay" :key="r.diena">
                <td>{{ r.diena }}</td>
                <td class="text-right">{{ r.rezervaciju_skaits }}</td>
              </tr>
            </tbody>
          </v-table>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const loading = ref(true)
const error = ref('')
const trainersBySport = ref([])
const bookingsByDay = ref([])

async function load() {
  loading.value = true
  error.value = ''
  try {
    const [a, b] = await Promise.all([
      api.get('/stats/trainers-by-sport'),
      api.get('/stats/bookings-by-day'),
    ])
    trainersBySport.value = a.data
    bookingsByDay.value = b.data
  } catch (e) {
    error.value = 'Neizdevās ielādēt statistiku. Pārbaudi Laravel serveri.'
  } finally {
    loading.value = false
  }
}

onMounted(load)
</script>