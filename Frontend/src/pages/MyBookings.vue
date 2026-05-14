<template>
  <v-container class="py-8" style="max-width: 980px;">
    <div class="d-flex align-center justify-space-between flex-wrap ga-3 mb-6">
      <div>
        <h1 class="text-h4 font-weight-black mb-1">Manas rezervācijas</h1>
        <div class="text-body-2" style="opacity:.75">
          Šeit vari apskatīt, rediģēt vai dzēst savas rezervācijas.
        </div>
      </div>

      <v-btn variant="tonal" color="primary" prepend-icon="mdi-refresh" @click="load">
        Atjaunot
      </v-btn>
    </div>

    <v-alert v-if="error" type="error" variant="tonal" class="mb-4" :text="error" />

    <v-card elevation="0" style="border-radius:18px;">
      <v-table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Treneris</th>
            <th>Datums</th>
            <th>Laiks</th>
            <th>Status</th>
            <th style="width:220px;"></th>
          </tr>
        </thead>

        <tbody>
          <tr v-if="loading">
            <td colspan="6" class="py-6">
              <v-progress-linear indeterminate />
            </td>
          </tr>

          <tr v-else-if="items.length === 0">
            <td colspan="6" class="py-6" style="opacity:.75;">
              Tev vēl nav rezervāciju.
            </td>
          </tr>

          <tr v-else v-for="b in items" :key="b.id">
            <td>{{ b.id }}</td>
            <td>{{ b.trainer?.name || b.trainer_id }}</td>
            <td>{{ b.date }}</td>
            <td>{{ String(b.time).slice(0,5) }}</td>
            <td>
              <v-chip size="small" variant="tonal" :color="b.status === 'approved' ? 'success' : (b.status === 'rejected' ? 'error' : 'primary')">
                {{ b.status }}
              </v-chip>
            </td>

            <td class="text-right">
              <v-btn size="small" variant="tonal" @click="openEdit(b)">Rediģēt</v-btn>
              <v-btn size="small" variant="tonal" color="error" class="ml-2" @click="remove(b)">Dzēst</v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card>

    <!-- Edit dialog -->
    <v-dialog v-model="dialog" max-width="520">
      <v-card class="pa-5" style="border-radius:18px;">
        <h2 class="text-h6 font-weight-bold mb-4">Rediģēt rezervāciju</h2>

        <v-text-field v-model="form.date" label="Datums (YYYY-MM-DD)" variant="solo" />
        <v-text-field v-model="form.time" label="Laiks (HH:MM)" variant="solo" />
        <v-textarea v-model="form.message" label="Ziņa" variant="solo" />

        <v-alert v-if="formError" type="error" variant="tonal" class="mb-3" :text="formError" />

        <div class="d-flex justify-end ga-2">
          <v-btn variant="text" @click="dialog=false">Aizvērt</v-btn>
          <v-btn color="primary" :loading="saving" @click="save">Saglabāt</v-btn>
        </div>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '../services/api'

const items = ref([])
const loading = ref(false)
const error = ref('')

const dialog = ref(false)
const saving = ref(false)
const formError = ref('')

const form = reactive({
  id: null,
  date: '',
  time: '',
  message: '',
})

async function load() {
  loading.value = true
  error.value = ''
  try {
    const { data } = await api.get('/my-bookings')
    items.value = data
  } catch (e) {
    const code = e?.response?.status
    if (code === 401) error.value = 'Lai skatītu rezervācijas, nepieciešams ielogoties.'
    else error.value = 'Neizdevās ielādēt rezervācijas.'
  } finally {
    loading.value = false
  }
}

function openEdit(b) {
  formError.value = ''
  form.id = b.id
  form.date = b.date
  form.time = String(b.time).slice(0,5)
  form.message = b.message || ''
  dialog.value = true
}

async function save() {
  formError.value = ''
  if (!form.date || !form.time) {
    formError.value = 'Datums un laiks ir obligāti.'
    return
  }

  saving.value = true
  try {
    await api.put(`/my-bookings/${form.id}`, {
      date: form.date,
      time: form.time.length === 5 ? form.time + ':00' : form.time,
      message: form.message,
    })
    dialog.value = false
    await load()
  } catch (e) {
    formError.value = e?.response?.data?.message || 'Neizdevās saglabāt.'
  } finally {
    saving.value = false
  }
}

async function remove(b) {
  if (!confirm(`Dzēst rezervāciju #${b.id}?`)) return
  try {
    await api.delete(`/my-bookings/${b.id}`)
    await load()
  } catch {
    alert('Neizdevās dzēst.')
  }
}

onMounted(load)
</script>