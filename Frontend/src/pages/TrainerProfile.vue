<template>
  <v-container class="py-8">
    <!-- Breadcrumbs -->
    <v-breadcrumbs :items="crumbs" class="mb-4" />

    <!-- Loading -->
    <v-row v-if="loading">
      <v-col cols="12">
        <v-skeleton-loader type="card, article, actions" />
      </v-col>
    </v-row>

    <!-- Error -->
    <v-alert
      v-else-if="error"
      type="error"
      variant="tonal"
      class="rounded-xl"
      :text="error"
    />

    <!-- Profile -->
    <v-row v-else>
      <v-col cols="12" md="8">
        <v-card class="profileCard" elevation="0">
          <div class="bg"></div>

          <v-card-text class="pa-6">
            <div class="d-flex align-center ga-4 flex-wrap">
              <v-avatar size="64" color="primary">
                <v-icon icon="mdi-account" />
              </v-avatar>

              <div>
                <div class="text-h4 font-weight-black">{{ trainer?.name }}</div>

                <div class="d-flex align-center ga-2 mt-1 flex-wrap">
                  <v-chip color="secondary" variant="tonal" size="small">
                    {{ trainer?.sport || 'Sports' }}
                  </v-chip>
                  <v-chip color="primary" variant="tonal" size="small">
                    Pieejams tiešsaistē
                  </v-chip>
                  <v-chip color="accent" variant="tonal" size="small">
                    4.9 (demo)
                  </v-chip>
                </div>
              </div>
            </div>

            <v-row class="mt-6" align="start">
              <v-col cols="12" md="7">
                <div class="text-body-1" style="opacity:.9">
                  {{ trainer?.description }}
                </div>

                <div class="d-flex ga-3 mt-5 flex-wrap">
                  <v-btn color="primary" variant="flat" class="btn" @click="contact">
                    <v-icon icon="mdi-message" start />
                    Sazināties
                  </v-btn>

                  <v-btn color="secondary" variant="tonal" class="btn" @click="openBooking">
                    <v-icon icon="mdi-calendar" start />
                    Rezervēt nodarbību
                  </v-btn>

                  <v-btn variant="text" @click="$router.back()">
                    <v-icon icon="mdi-arrow-left" start />
                    Atpakaļ
                  </v-btn>
                </div>
              </v-col>

              <v-col cols="12" md="5">
                <div class="info">
                  <div class="text-subtitle-1 font-weight-bold mb-2">Ātrā informācija</div>
                  <div class="d-flex align-center ga-2 mb-2">
                    <v-icon icon="mdi-map-marker" />
                    <span>Rīga (demo)</span>
                  </div>
                  <div class="d-flex align-center ga-2 mb-2">
                    <v-icon icon="mdi-cash" />
                    <span>No 20€/h </span>
                  </div>
                  <div class="d-flex align-center ga-2 mb-2">
                    <v-icon icon="mdi-clock-outline" />
                    <span>Elastīgs grafiks</span>
                  </div>

                  <v-divider class="my-4" />

                  <v-btn color="primary" variant="flat" class="btnWide" @click="$router.push('/treneri')">
                    Skatīt citus trenerus
                  </v-btn>
                </div>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>

      <!-- Photo -->
      <v-col cols="12" md="4">
        <v-card class="photoCard" elevation="0">
          <v-img :src="photoSrc" height="360" cover />
        </v-card>
      </v-col>
    </v-row>

    <!-- BOOKING DIALOG -->
    <v-dialog v-model="bookingDialog" max-width="520">
      <v-card class="rounded-xl">
        <v-card-title class="font-weight-black">Rezervēt nodarbību</v-card-title>

        <v-card-text class="pt-2">
          <div class="text-body-2 mb-3" style="opacity:.75">
            Izvēlies datumu un laiku. Pilnībā aizņemtas dienas nav izvēlamas. Rezervēt var tikai sākot no rītdienas.
          </div>

          <!-- Date picker -->
          <v-date-picker
            v-model="form.date"
            :min="minDate"
            :allowed-dates="allowedDates"
            show-adjacent-months
            class="mb-4"
          />

          <!-- Time select -->
          <v-select
            v-model="form.time"
            :items="timeOptions"
            label="Laiks"
            variant="solo"
            density="comfortable"
            :disabled="!selectedDateStr || timeOptions.length === 0"
            hide-details
          />

          <div v-if="selectedDateStr && timeOptions.length === 0" class="text-caption mt-2" style="opacity:.7">
            Šai dienai nav pieejamu laiku.
          </div>

          <!-- Busy times chips -->
          <div v-if="selectedDateStr && busyTimesForSelectedDay.length" class="mt-3">
            <div class="text-caption mb-1" style="opacity:.7">Aizņemti laiki šajā dienā:</div>
            <div class="d-flex flex-wrap ga-2">
              <v-chip
                v-for="t in busyTimesForSelectedDay"
                :key="t"
                size="small"
                color="error"
                variant="tonal"
              >
                {{ t }}
              </v-chip>
            </div>
          </div>

          
          

          <v-alert
            v-if="submitError"
            type="error"
            variant="tonal"
            class="mt-4 rounded-xl"
            :text="submitError"
          />

          <v-alert
            v-if="submitOk"
            type="success"
            variant="tonal"
            class="mt-4 rounded-xl"
            text="Rezervācija nosūtīta!"
          />
        </v-card-text>

        <v-card-actions class="px-4 pb-4">
          <v-btn variant="text" @click="bookingDialog = false">Aizvērt</v-btn>
          <v-spacer />
          <v-btn
            color="primary"
            variant="flat"
            :loading="submitting"
            :disabled="!canSubmit || submitting"
            @click="submitBooking"
          >
            Nosūtīt
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const router = useRouter()

/* ---------------------------
   Trainer
---------------------------- */
const trainer = ref({ id: null, name: '', sport: '', description: '' })
const loading = ref(true)
const error = ref('')

const crumbs = computed(() => [
  { title: 'Sākums', to: '/' },
  { title: 'Treneri', to: '/treneri' },
  { title: 'Profils', disabled: true },
])

const photoSrc = computed(() => `/trainers/${route.params.id}.jpg`)

/* ---------------------------
   Booking dialog + UI state
---------------------------- */
const bookingDialog = ref(false)
const submitting = ref(false)
const submitError = ref('')
const submitOk = ref(false)

// Busy slots: [{id, date:'YYYY-MM-DD', time:'HH:MM:SS' or 'HH:MM'}]
const busy = ref([])

/* ---------------------------
   Form 
---------------------------- */
const form = ref({
  date: null,   // v-date-picker value
  time: '',     // 'HH:MM'
  message: '',
})

/* ---------------------------
   Helpers
---------------------------- */
function pad(n) { return String(n).padStart(2, '0') }

function dateStr(val) {
  if (!val) return ''
  if (typeof val === 'string') return val.slice(0, 10)

  const d = new Date(val)
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`
}

function normalizeTime(t) {
  if (!t) return ''
  return t.length >= 5 ? t.slice(0, 5) : t
}

// rītdiena (min datums)
function tomorrowStr() {
  const d = new Date()
  d.setHours(0, 0, 0, 0)
  d.setDate(d.getDate() + 1)
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`
}
const minDate = computed(() => tomorrowStr())

function isBeforeTomorrow(dStr) {
  if (!dStr) return true
  return dStr < minDate.value
}

const selectedDateStr = computed(() => dateStr(form.value.date))

/* ---------------------------
   Time slots (09:00–19:00, every 60 minutes)
---------------------------- */
const allTimes = Array.from({ length: 11 }, (_, i) => {
  const h = 9 + i
  return `${String(h).padStart(2, '0')}:00`
})

const busyTimesForSelectedDay = computed(() => {
  const d = selectedDateStr.value
  if (!d) return []
  const times = busy.value
    .filter(x => x.date === d)
    .map(x => normalizeTime(x.time))
  return Array.from(new Set(times)).sort()
})

const timeOptions = computed(() => {
  const d = selectedDateStr.value
  if (!d) return []
  if (isBeforeTomorrow(d)) return []
  const busySet = new Set(busyTimesForSelectedDay.value)
  return allTimes.filter(t => !busySet.has(t))
})

const allowedDates = (val) => {
  const d = dateStr(val)
  if (!d) return true
  if (isBeforeTomorrow(d)) return false
  const count = busy.value.filter(x => x.date === d).length
  return count < allTimes.length
}

const canSubmit = computed(() => {
  return !!selectedDateStr.value && form.value.time.trim().length > 0
})

/* ---------------------------
   Loads
---------------------------- */
async function loadTrainer() {
  loading.value = true
  error.value = ''
  try {
    const { data } = await api.get(`/treneri/${route.params.id}`)
    trainer.value = data
  } catch {
    error.value = 'Pārbaudi, vai darbojas Laravel serveris un API maršruti.'
  } finally {
    loading.value = false
  }
}

async function loadBusy() {
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() + 30)
  const fmt = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`

  const { data } = await api.get(`/trainers/${route.params.id}/bookings`, {
    params: { from: fmt(from), to: fmt(to) },
  })

  busy.value = Array.isArray(data) ? data : []
}

/* ---------------------------
   Actions
---------------------------- */
function contact() {
  alert('Demo: šeit būs saziņas forma vai čats.')
}

async function openBooking() {
  submitError.value = ''
  submitOk.value = false
  bookingDialog.value = true

  try {
    await loadBusy()
  } catch {
    submitError.value = 'Neizdevās ielādēt aizņemtos laikus. Pārbaudi API /trainers/{id}/bookings.'
  }
}

async function submitBooking() {
  submitting.value = true
  submitError.value = ''
  submitOk.value = false

  const d = selectedDateStr.value

  if (!d) {
    submitError.value = 'Izvēlies datumu.'
    submitting.value = false
    return
  }

  if (isBeforeTomorrow(d)) {
    submitError.value = 'Rezervāciju var veikt tikai sākot no rītdienas.'
    submitting.value = false
    return
  }

  if (!form.value.time) {
    submitError.value = 'Izvēlies laiku.'
    submitting.value = false
    return
  }

  if (busyTimesForSelectedDay.value.includes(form.value.time)) {
    submitError.value = 'Šis laiks jau ir aizņemts.'
    submitting.value = false
    return
  }

  // Ņemam user datus no localStorage (tāds kā tu ielogojies)
  const u = (() => {
    try { return JSON.parse(localStorage.getItem('user') || 'null') }
    catch { return null }
  })()

  try {
    await api.post('/bookings', {
      trainer_id: Number(route.params.id),
      date: d,
      time: form.value.time,
      message: form.value.message || null,

      // ja backend vēl prasa client_name/client_email
      client_name: u?.name || 'Lietotājs',
      client_email: u?.email || 'user@example.com',
    })

    submitOk.value = true
    await loadBusy()
    form.value.message = ''
  } catch (e) {
    submitError.value =
      e?.response?.data?.message ||
      'Neizdevās nosūtīt rezervāciju. Pārbaudi ievadītos datus.'
  } finally {
    submitting.value = false
  }
}

onMounted(async () => {
  await loadTrainer()
})
</script>

<style scoped>
.profileCard {
  border-radius: 26px;
  overflow: hidden;
  position: relative;
  background: color-mix(in srgb, var(--v-theme-surface) 90%, transparent);
  border: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
}

.bg {
  position: absolute;
  inset: 0;
  background:
    radial-gradient(circle at 20% 20%, color-mix(in srgb, var(--v-theme-primary) 40%, transparent), transparent 55%),
    radial-gradient(circle at 80% 30%, color-mix(in srgb, var(--v-theme-secondary) 35%, transparent), transparent 55%),
    radial-gradient(circle at 50% 90%, color-mix(in srgb, var(--v-theme-tertiary) 25%, transparent), transparent 60%);
  opacity: 0.9;
  pointer-events: none;
}

.info {
  padding: 18px;
  border-radius: 20px;
  background: color-mix(in srgb, var(--v-theme-surface) 92%, transparent);
  border: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
}

.photoCard {
  border-radius: 26px;
  overflow: hidden;
  border: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
}

.btn {
  border-radius: 14px;
  font-weight: 800;
}

.btnWide {
  width: 100%;
  border-radius: 14px;
  font-weight: 900;
}
</style>