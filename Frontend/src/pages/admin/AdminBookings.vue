<template>
  <v-card class="pa-4" elevation="0" style="border-radius:18px;">
    <div class="d-flex align-center justify-space-between mb-4">
      <h2 class="text-h6 font-weight-bold">Rezervācijas</h2>
      <v-btn variant="tonal" @click="load">Atjaunot</v-btn>
    </div>

    <v-table>
      <thead>
        <tr>
          <th>ID</th><th>Treneris</th><th>Vārds</th><th>E-pasts</th><th>Datums</th><th>Laiks</th><th>Status</th><th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="b in bookings" :key="b.id">
          <td>{{ b.id }}</td>
          <td>{{ b.trainer?.name || b.trainer_id }}</td>
          <td>{{ b.client_name }}</td>
          <td>{{ b.client_email }}</td>
          <td>{{ b.date }}</td>
          <td>{{ String(b.time).slice(0,5) }}</td>
          <td>
            <v-select
              :items="['pending','approved','rejected']"
              v-model="b.status"
              density="compact"
              variant="solo"
              hide-details
              style="max-width:160px;"
              @update:modelValue="updateStatus(b)"
            />
          </td>
          <td class="text-right">
            <v-btn size="small" color="error" variant="tonal" @click="remove(b.id)">Dzēst</v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>
  </v-card>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const bookings = ref([])

async function load() {
  const { data } = await api.get('/admin/bookings')
  bookings.value = data
}

async function updateStatus(b) {
  await api.put(`/admin/bookings/${b.id}`, { status: b.status })
}

async function remove(id) {
  await api.delete(`/admin/bookings/${id}`)
  await load()
}

onMounted(load)
</script>