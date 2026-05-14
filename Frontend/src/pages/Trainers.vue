<template>
  <v-container class="py-8 trainersPage">
    <!-- Header -->
    <div class="d-flex align-center justify-space-between flex-wrap ga-3 mb-6">
      <div>
        <h1 class="text-h4 font-weight-black mb-1">Treneri</h1>
        <div class="text-body-2" style="opacity:.75">
          Meklē un filtrē trenerus (advanced filter) ar servera puses vaicājumiem.
        </div>
      </div>

      <div class="d-flex ga-2 flex-wrap">
        <v-btn color="primary" variant="tonal" prepend-icon="mdi-refresh" @click="reload">
          Atjaunot
        </v-btn>
        <v-btn variant="tonal" prepend-icon="mdi-filter-variant" @click="showFilters = !showFilters">
          Filtri
        </v-btn>
        <v-btn variant="text" @click="resetFilters">Notīrīt</v-btn>
      </div>
    </div>

    <!-- Filters -->
    <v-expand-transition>
      <v-card v-if="showFilters" class="pa-4 mb-6 filtersCard" elevation="0">
        <v-row class="ga-3">
          <v-col cols="12" md="6">
            <v-text-field
              v-model="q"
              label="Meklēt (vārds / apraksts / sports / pilsēta)"
              prepend-inner-icon="mdi-magnify"
              variant="solo"
              density="comfortable"
              hide-details
              @keyup.enter="applyFilters"
            />
          </v-col>

          <v-col cols="12" md="3">
            <v-select
              v-model="sportId"
              :items="sportOptions"
              item-title="title"
              item-value="value"
              label="Sports"
              prepend-inner-icon="mdi-filter"
              variant="solo"
              density="comfortable"
              hide-details
              clearable
            />
          </v-col>

          <v-col cols="12" md="3">
            <v-select
              v-model="city"
              :items="cityOptions"
              label="Pilsēta"
              prepend-inner-icon="mdi-map-marker"
              variant="solo"
              density="comfortable"
              hide-details
              clearable
            />
          </v-col>

          <v-col cols="12" md="6">
            <div class="d-flex align-center justify-space-between mb-2">
              <div class="text-subtitle-2 font-weight-bold">Cena (€/h)</div>
              <div class="text-body-2" style="opacity:.75">
                {{ priceRange[0] }} – {{ priceRange[1] }}
              </div>
            </div>
            <v-range-slider
              v-model="priceRange"
              :min="priceMin"
              :max="priceMax"
              :step="1"
              hide-details
            />
          </v-col>

          <v-col cols="12" md="3">
            <v-switch
              v-model="onlyOnline"
              inset
              color="primary"
              label="Tikai tiešsaistē"
            />
          </v-col>

          <v-col cols="12" md="3">
            <v-select
              v-model="sortBy"
              :items="sortOptions"
              item-title="title"
              item-value="value"
              label="Kārtot pēc"
              prepend-inner-icon="mdi-sort"
              variant="solo"
              density="comfortable"
              hide-details
            />
            <div class="d-flex ga-2 mt-2">
              <v-btn
                size="small"
                :variant="sortDir === 'asc' ? 'flat' : 'tonal'"
                color="primary"
                @click="sortDir = 'asc'"
              >
                Augoši
              </v-btn>
              <v-btn
                size="small"
                :variant="sortDir === 'desc' ? 'flat' : 'tonal'"
                color="primary"
                @click="sortDir = 'desc'"
              >
                Dilstoši
              </v-btn>
            </div>
          </v-col>

          <v-col cols="12">
            <div class="d-flex ga-2 flex-wrap justify-end">
              <v-btn variant="text" @click="resetFilters">Notīrīt</v-btn>
              <v-btn color="primary" prepend-icon="mdi-magnify" @click="applyFilters">
                Meklēt
              </v-btn>
            </div>
          </v-col>
        </v-row>
      </v-card>
    </v-expand-transition>

    <!-- Error -->
    <v-alert
      v-if="error"
      type="error"
      variant="tonal"
      class="rounded-xl mb-4"
      title="Kļūda"
      :text="error"
    />

    <!-- Loading -->
    <v-row v-if="loading">
      <v-col cols="12" sm="6" md="4" v-for="i in 6" :key="i">
        <v-skeleton-loader type="image, article, actions" class="rounded-xl" />
      </v-col>
    </v-row>

    <!-- Empty -->
    <v-alert
      v-else-if="trainers.length === 0"
      type="info"
      variant="tonal"
      class="rounded-xl"
      title="Nav rezultātu"
      text="Pamēģini noņemt daļu filtru vai mainīt meklēšanas tekstu."
    />

    <!-- Cards -->
    <v-row v-else>
      <v-col cols="12" sm="6" md="4" v-for="t in trainers" :key="t.id">
        <v-card class="trainerCard" elevation="0">
          <v-img :src="photoSrc(t.id)" height="220" cover class="trainerImg">
            <div class="imgOverlay d-flex ga-2">
              <v-chip color="secondary" variant="tonal" size="small">
                {{ t.sport_name || '—' }}
              </v-chip>
              <v-chip v-if="t.online" color="primary" variant="tonal" size="small">
                Online
              </v-chip>
            </div>
          </v-img>

          <v-card-title class="text-h6 font-weight-bold">
            {{ t.name }}
          </v-card-title>

          <v-card-subtitle class="pb-0">
            <span v-if="t.city" class="mr-3">
              <v-icon icon="mdi-map-marker" size="16" class="mr-1" />
              {{ t.city }}
            </span>
            <span v-if="t.price_per_hour != null">
              <v-icon icon="mdi-currency-eur" size="16" class="mr-1" />
              {{ t.price_per_hour }}/h
            </span>
          </v-card-subtitle>

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

    <!-- Pagination -->
    <div class="d-flex justify-center mt-6" v-if="meta.lastPage > 1">
      <v-pagination
        v-model="page"
        :length="meta.lastPage"
        :total-visible="7"
        @update:modelValue="applyFilters"
      />
    </div>

    
  </v-container>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import api from '../services/api'
import { useRoute } from 'vue-router'

const loading = ref(false)
const error = ref('')
const route = useRoute()

const trainers = ref([])
const meta = ref({ page: 1, lastPage: 1, total: 0 })

// UI state
const showFilters = ref(true)

// filters
const q = ref('')
const sportId = ref(null) // sports.id
const city = ref(null)
const onlyOnline = ref(false)

const sortBy = ref('name') // name|price|city|sport
const sortDir = ref('asc') // asc|desc
const sortOptions = [
  { title: 'Vārds', value: 'name' },
  { title: 'Cena', value: 'price' },
  { title: 'Pilsēta', value: 'city' },
  { title: 'Sports', value: 'sport' },
]

// price range
const priceMin = ref(0)
const priceMax = ref(60)
const priceRange = ref([0, 60])

// pagination
const page = ref(1)

const photoSrc = (id) => `/trainers/${id}.jpg`

// options from current result set (vari arī ielādēt no /api/admin/sports, bet šeit pietiek)
const sportOptions = computed(() => {
  const map = new Map()
  trainers.value.forEach(t => {
    if (t.sport_id && t.sport_name) map.set(t.sport_id, t.sport_name)
  })
  return Array.from(map.entries())
    .map(([value, title]) => ({ value, title }))
    .sort((a, b) => a.title.localeCompare(b.title))
})
function applyQueryToFilters() {
  // q
  if (typeof route.query.q === 'string') q.value = route.query.q

  // sport_id (ja sūti)
  if (route.query.sport_id) sportId.value = Number(route.query.sport_id)

  // city
  if (typeof route.query.city === 'string') city.value = route.query.city

  // online=1
  if (route.query.online === '1' || route.query.online === 1) onlyOnline.value = true

  // cena
  const min = route.query.min_price ? Number(route.query.min_price) : null
  const max = route.query.max_price ? Number(route.query.max_price) : null
  if (min !== null && !Number.isNaN(min) && max !== null && !Number.isNaN(max)) {
    priceRange.value = [min, max]
}
 // sort + dir
  if (typeof route.query.sort === 'string') sortBy.value = route.query.sort
  if (route.query.dir === 'asc' || route.query.dir === 'desc') sortDir.value = route.query.dir
}
const cityOptions = computed(() => {
  const set = new Set(trainers.value.map(t => t.city).filter(Boolean))
  return Array.from(set).sort()
})

async function load() {
  loading.value = true
  error.value = ''

  try {
    const params = {
      q: q.value || undefined,
      sport_id: sportId.value || undefined,
      city: city.value || undefined,
      online: onlyOnline.value ? 1 : undefined,
      min_price: priceRange.value[0],
      max_price: priceRange.value[1],
      sort: sortBy.value,
      dir: sortDir.value,
      per_page: 12,
      page: page.value,
    }

    const { data } = await api.get('/treneri', { params })

    // Laravel paginate struktūra
    trainers.value = data.data || []
    meta.value = {
      page: data.current_page || 1,
      lastPage: data.last_page || 1,
      total: data.total || 0,
    }

    // initial range (tikai pirmajā ielādē)
    if (meta.value.page === 1 && priceMin.value === 0 && priceMax.value === 60 && trainers.value.length) {
      const prices = trainers.value
        .map(t => Number(t.price_per_hour))
        .filter(n => !Number.isNaN(n))

      if (prices.length) {
        const min = Math.min(...prices)
        const max = Math.max(...prices)
        priceMin.value = min
        priceMax.value = max
        priceRange.value = [min, max]
      }
    }
  } catch (e) {
    const code = e?.response?.status
    if (code === 422) error.value = 'Filtru vērtības nav derīgas (422).'
    else error.value = 'Neizdevās ielādēt trenerus. Pārbaudi Laravel serveri un API.'
  } finally {
    loading.value = false
  }
}

function applyFilters() {
  page.value = 1
  load()
}

function reload() {
  load()
}

function resetFilters() {
  q.value = ''
  sportId.value = null
  city.value = null
  onlyOnline.value = false
  sortBy.value = 'name'
  sortDir.value = 'asc'
  priceRange.value = [priceMin.value, priceMax.value]
  page.value = 1
  load()
}

onMounted(() => {
  applyQueryToFilters()
  load()
})

// Auto-search (mazāk agressīvi): ja mainās select/switch/sort, pārlādē
watch([sportId, city, onlyOnline, sortBy, sortDir], () => {
  applyQueryToFilters()
  page.value = 1
  load()
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

<style scoped>
.filtersCard {
  border-radius: 18px;
  border: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
  background: color-mix(in srgb, var(--v-theme-surface) 94%, transparent);
}

.trainerCard {
  border-radius: 22px;
  overflow: hidden;
  border: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
  background: color-mix(in srgb, var(--v-theme-surface) 92%, transparent);
  transition: transform 160ms ease, box-shadow 160ms ease;
}

.trainerCard:hover {
  transform: translateY(-4px);
  box-shadow: 0 16px 40px rgba(0,0,0,0.12);
}

.trainerImg {
  border-bottom: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
}

.imgOverlay {
  position: absolute;
  left: 12px;
  top: 12px;
}

.trainerBtn {
  border-radius: 14px;
  font-weight: 900;
}
</style>