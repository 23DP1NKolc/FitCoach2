<template>
  <v-card class="pa-4" elevation="0" style="border-radius:18px;">
    <div class="d-flex align-center justify-space-between mb-4">
      <h2 class="text-h6 font-weight-bold">Treneri</h2>
      <v-btn color="primary" @click="openCreate">Pievienot treneri</v-btn>
    </div>

    <v-table>
      <thead>
        <tr>
          <th>ID</th><th>Vārds</th><th>Sports</th><th>Pilsēta</th><th>Cena</th><th>Online</th><th></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="t in trainers" :key="t.id">
          <td>{{ t.id }}</td>
          <td>{{ t.name }}</td>
          <td>{{ t.sportRel?.name || t.sport || '-' }}</td>
          <td>{{ t.city || '-' }}</td>
          <td>{{ t.price_per_hour ?? '-' }}</td>
          <td>{{ t.online ? 'Jā' : 'Nē' }}</td>
          <td class="text-right">
            <v-btn size="small" variant="tonal" @click="openEdit(t)">Labot</v-btn>
            <v-btn size="small" color="error" variant="tonal" class="ml-2" @click="remove(t.id)">Dzēst</v-btn>
          </td>
        </tr>
      </tbody>
    </v-table>

    <v-dialog v-model="dialog" max-width="560">
      <v-card class="pa-4" style="border-radius:18px;">
        <h3 class="text-h6 font-weight-bold mb-3">{{ editing ? 'Labot treneri' : 'Pievienot treneri' }}</h3>

        <v-text-field v-model="form.name" label="Vārds" variant="solo" />
        <v-textarea v-model="form.description" label="Apraksts" variant="solo" />
        <v-select
          v-model="form.sport_id"
          :items="sports"
          item-title="name"
          item-value="id"
          label="Sports"
          variant="solo"
          clearable
        />
        <v-text-field v-model="form.city" label="Pilsēta" variant="solo" />
        <v-text-field v-model="form.price_per_hour" label="Cena €/h" variant="solo" />
        <v-switch v-model="form.online" label="Pieejams tiešsaistē" />

        <v-alert v-if="err" type="error" variant="tonal" :text="err" class="mb-3" />
        <div class="d-flex justify-end ga-2">
          <v-btn variant="text" @click="dialog=false">Atcelt</v-btn>
          <v-btn color="primary" @click="save">Saglabāt</v-btn>
        </div>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../../services/api'

const trainers = ref([])
const sports = ref([])

const dialog = ref(false)
const editing = ref(false)
const editId = ref(null)
const err = ref('')

const form = ref({
  name: '',
  description: '',
  sport_id: null,
  city: '',
  price_per_hour: null,
  online: true,
})

async function load() {
  const [t, s] = await Promise.all([
    api.get('/admin/trainers'),
    api.get('/admin/sports'),
  ])
  trainers.value = t.data
  sports.value = s.data
}

function openCreate() {
  editing.value = false
  editId.value = null
  err.value = ''
  form.value = { name:'', description:'', sport_id:null, city:'', price_per_hour:null, online:true }
  dialog.value = true
}

function openEdit(t) {
  editing.value = true
  editId.value = t.id
  err.value = ''
  form.value = {
    name: t.name,
    description: t.description,
    sport_id: t.sport_id,
    city: t.city || '',
    price_per_hour: t.price_per_hour,
    online: !!t.online,
  }
  dialog.value = true
}

async function save() {
  err.value = ''
  try {
    if (editing.value) {
      await api.put(`/admin/trainers/${editId.value}`, form.value)
    } else {
      await api.post('/admin/trainers', form.value)
    }
    dialog.value = false
    await load()
  } catch {
    err.value = 'Neizdevās saglabāt.'
  }
}

async function remove(id) {
  await api.delete(`/admin/trainers/${id}`)
  await load()
}

onMounted(load)
</script>