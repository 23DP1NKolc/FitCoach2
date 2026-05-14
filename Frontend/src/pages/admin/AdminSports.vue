<template>
  <div>
    <v-alert v-if="error" type="error" variant="tonal" class="mb-4" :text="error" />

    <div class="d-flex justify-end mb-4">
      <v-btn color="primary" @click="openCreate" prepend-icon="mdi-plus">
        Pievienot
      </v-btn>
    </div>

    <v-card elevation="0" style="border-radius:16px;">
      <v-table>
        <thead>
          <tr>
            <th style="width:90px;">ID</th>
            <th>Nosaukums</th>
            <th style="width:160px;"></th>
          </tr>
        </thead>

        <tbody>
          <tr v-if="loading">
            <td colspan="3" class="py-6">
              <v-progress-linear indeterminate />
            </td>
          </tr>

          <tr v-else-if="sports.length === 0">
            <td colspan="3" class="py-6" style="opacity:.75;">
              Nav sporta veidu. Spied “Pievienot”.
            </td>
          </tr>

          <tr v-else v-for="s in sports" :key="s.id">
            <td>{{ s.id }}</td>
            <td>{{ s.name }}</td>
            <td class="text-right">
              <v-btn size="small" variant="text" @click="openEdit(s)">Labot</v-btn>
              <v-btn size="small" variant="text" color="error" @click="removeSport(s)">Dzēst</v-btn>
            </td>
          </tr>
        </tbody>
      </v-table>
    </v-card>

    <v-dialog v-model="dialog" max-width="520">
      <v-card class="pa-5" style="border-radius:16px;">
        <h3 class="text-h6 font-weight-bold mb-4">{{ editing ? 'Labot sportu' : 'Pievienot sportu' }}</h3>

        <v-text-field v-model="form.name" label="Nosaukums" variant="solo" />

        <v-alert v-if="formError" type="error" variant="tonal" class="mb-3" :text="formError" />

        <div class="d-flex justify-end ga-2">
          <v-btn variant="text" @click="dialog=false">Aizvērt</v-btn>
          <v-btn color="primary" :loading="saving" @click="saveSport">Saglabāt</v-btn>
        </div>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '../../services/api'

const sports = ref([])
const loading = ref(false)
const error = ref('')

const dialog = ref(false)
const editing = ref(false)
const saving = ref(false)
const formError = ref('')
const form = reactive({ id: null, name: '' })

async function load() {
  loading.value = true
  error.value = ''
  try {
    const { data } = await api.get('/admin/sports')
    sports.value = data
  } catch (e) {
    const code = e?.response?.status
    if (code === 401) error.value = 'Nav pieslēgts. Ienāc sistēmā.'
    else if (code === 403) error.value = 'Nav piekļuves. Nepieciešams admin.'
    else error.value = 'Servera kļūda.'
  } finally {
    loading.value = false
  }
}

onMounted(load)

function openCreate() {
  editing.value = false
  form.id = null
  form.name = ''
  formError.value = ''
  dialog.value = true
}

function openEdit(s) {
  editing.value = true
  form.id = s.id
  form.name = s.name
  formError.value = ''
  dialog.value = true
}

async function saveSport() {
  formError.value = ''
  if (!form.name.trim()) {
    formError.value = 'Ievadi sporta nosaukumu.'
    return
  }

  saving.value = true
  try {
    if (editing.value) {
      await api.put(`/admin/sports/${form.id}`, { name: form.name })
    } else {
      await api.post('/admin/sports', { name: form.name })
    }
    dialog.value = false
    await load()
  } catch (e) {
    formError.value = e?.response?.data?.message || 'Neizdevās saglabāt.'
  } finally {
    saving.value = false
  }
}

async function removeSport(s) {
  if (!confirm(`Dzēst sportu "${s.name}"?`)) return
  try {
    await api.delete(`/admin/sports/${s.id}`)
    await load()
  } catch {
    alert('Neizdevās dzēst.')
  }
}
</script>