
<template>
  <v-container class="py-8 trainersPage">
    <!-- Header -->
    <div class="d-flex align-center justify-space-between flex-wrap ga-3 mb-6">
      <div>
        <h1 class="text-h4 font-weight-black mb-1">Treneri</h1>
        <div class="text-body-2" style="opacity:.75">
          Meklē un filtrē trenerus pēc specialitātes.
        </div>
      </div>

      <v-btn color="primary" variant="tonal" prepend-icon="mdi-refresh" @click="reload">
        Atjaunot
      </v-btn>
    </div>

    <!-- Filters -->
    <v-card class="pa-4 mb-6 filtersCard" elevation="0">
      <v-row class="ga-2" align="center">
        <v-col cols="12" md="7">
          <v-text-field
            v-model="q"
            label="Meklēt pēc vārda vai sporta veida"
            prepend-inner-icon="mdi-magnify"
            variant="solo"
            density="comfortable"
            hide-details
          />
        </v-col>

        <v-col cols="12" md="5">
          <v-select
            v-model="sport"
            :items="sportOptions"
            label="Filtrs: sporta veids"
            prepend-inner-icon="mdi-filter"
            variant="solo"
            density="comfortable"
            hide-details
            clearable
          />
        </v-col>
      </v-row>
    </v-card>

    <!-- Loading -->
    <v-row v-if="loading">
      <v-col cols="12" sm="6" md="4" v-for="i in 6" :key="i">
        <v-skeleton-loader type="image, article, actions" class="rounded-xl" />
      </v-col>
    </v-row>

    <!-- Error -->
    <v-alert
      v-else-if="error"
      type="error"
      variant="tonal"
      class="rounded-xl"
      title="Neizdevās ielādēt trenerus"
      :text="error"
    />

    <!-- Empty -->
    <v-alert
      v-else-if="filtered.length === 0"
      type="info"
      variant="tonal"
      class="rounded-xl"
      title="Nav atrasts neviens treneris"
      text="Pamēģini citu meklēšanas tekstu vai noņem filtru."
    />

    <!-- Cards -->
    <v-row v-else>
      <v-col cols="12" sm="6" md="4" v-for="t in filtered" :key="t.id">
        <v-card class="trainerCard" elevation="0">
          <!-- IMAGE TOP -->
          <v-img
            :src="photoSrc(t.id)"
            height="220"
            cover
            class="trainerImg"
          >
            <div class="imgOverlay">
              <v-chip color="secondary" variant="tonal" size="small">
                {{ t.sport }}
              </v-chip>
            </div>
          </v-img>

          <v-card-title class="text-h6 font-weight-bold">
            {{ t.name }}
          </v-card-title>

          <v-card-text class="text-body-2" style="opacity:.85">
            {{ short(t.description) }}
          </v-card-text>

          <v-card-actions class="px-4 pb-4">
            <v-btn
              color="primary"
              variant="flat"
              class="trainerBtn"
              @click="$router.push(`/treneri/${t.id}`)"
            >
              Skatīt profilu
            </v-btn>

            <v-spacer />

            <v-btn
              color="primary"
              variant="tonal"
              icon="mdi-heart-outline"
              @click="favorite(t)"
            />
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import '../assets/trainers.css'
import { ref, computed, onMounted } from 'vue'
import api from '../services/api'

const loading = ref(true)
const error = ref('')
const trainers = ref([])

const q = ref('')
const sport = ref(null)

// Bildes liec: Frontend/public/trainers/1.jpg, 2.jpg, 3.jpg ...
const photoSrc = (id) => `/trainers/${id}.jpg`

async function load() {
  loading.value = true
  error.value = ''
  try {
    const { data } = await api.get('/treneri')
    trainers.value = data
  } catch (e) {
    error.value = 'Pārbaudi, vai darbojas Laravel serveris un API maršruti.'
  } finally {
    loading.value = false
  }
}

function reload() {
  load()
}

onMounted(load)

const sportOptions = computed(() => {
  const set = new Set(trainers.value.map(t => t.sport).filter(Boolean))
  return Array.from(set).sort()
})

const filtered = computed(() => {
  const query = q.value.trim().toLowerCase()

  return trainers.value.filter(t => {
    const matchesSport = !sport.value || t.sport === sport.value
    if (!matchesSport) return false

    if (!query) return true

    const hay = `${t.name ?? ''} ${t.sport ?? ''} ${t.description ?? ''}`.toLowerCase()
    return hay.includes(query)
  })
})

function short(text) {
  if (!text) return ''
  const t = text.trim()
  return t.length > 120 ? t.slice(0, 120) + '...' : t
}

function favorite(t) {
  alert(`Demo: pievienots favorītos — ${t.name}`)
}
</script>
