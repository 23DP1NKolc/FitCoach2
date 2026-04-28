<template>
  <v-container class="py-10">
    <v-breadcrumbs :items="crumbs" class="mb-6" />

    <!-- Loading -->
    <v-skeleton-loader
      v-if="loading"
      type="card, article, actions"
      class="rounded-xl"
    />

    <!-- Error -->
    <v-alert
      v-else-if="error"
      type="error"
      variant="tonal"
      title="Neizdevās ielādēt profilu"
      :text="error"
      class="rounded-xl"
    />

    <!-- Content -->
    <v-card v-else class="profileCard" elevation="0">
      <div class="bg" />

      <v-row class="pa-6 pa-md-10" align="center">
        <!-- Left -->
        <v-col cols="12" md="8">
          <div class="d-flex align-center ga-4">
            <v-avatar size="72" color="primary" class="shadow">
              <v-icon icon="mdi-account" size="32" />
            </v-avatar>

            <div>
              <h1 class="text-h4 font-weight-black mb-1">
                {{ trainer.name }}
              </h1>

              <div class="d-flex flex-wrap ga-2">
                <v-chip color="secondary" variant="tonal">
                  {{ trainer.sport }}
                </v-chip>
                <v-chip color="primary" variant="tonal">
                  Pieejams tiešsaistē
                </v-chip>
                <v-chip color="accent" variant="tonal">
                  4.9 (demo)
                </v-chip>
              </div>
            </div>
          </div>

          <!-- Foto (no Frontend/public/trainers/{id}.jpg) -->
          <v-img
            :src="photoSrc"
            height="260"
            cover
            class="rounded-xl mt-6 mb-6"
          />

          <p class="text-body-1 muted">
            {{ trainer.description }}
          </p>

          <div class="d-flex flex-wrap ga-3 mt-6">
            <v-btn
              color="primary"
              size="large"
              class="btn"
              prepend-icon="mdi-message"
              @click="contact"
            >
              Sazināties
            </v-btn>

            <v-btn
              color="secondary"
              variant="tonal"
              size="large"
              class="btn"
              prepend-icon="mdi-calendar"
              @click="openBooking"
            >
              Rezervēt nodarbību
            </v-btn>

            <v-btn
              variant="text"
              size="large"
              prepend-icon="mdi-arrow-left"
              @click="$router.push('/treneri')"
            >
              Atpakaļ
            </v-btn>
          </div>
        </v-col>

        <!-- Right -->
        <v-col cols="12" md="4">
          <v-card class="sideCard" elevation="0">
            <h3 class="mb-4">Ātrā informācija</h3>

            <div class="infoRow">
              <v-icon icon="mdi-map-marker" />
              <span>Rīga (demo)</span>
            </div>

            <div class="infoRow">
              <v-icon icon="mdi-cash" />
              <span>No 20€/h (demo)</span>
            </div>

            <div class="infoRow">
              <v-icon icon="mdi-clock-outline" />
              <span>Elastīgs grafiks (demo)</span>
            </div>

            <v-divider class="my-5" />

            <v-btn
              block
              color="primary"
              variant="flat"
              class="btn"
              @click="$router.push('/treneri')"
            >
              Skatīt citus trenerus
            </v-btn>
          </v-card>
        </v-col>
      </v-row>
    </v-card>

    <!-- Booking Dialog with calendar + busy times -->
    <v-dialog v-model="bookingDialog" max-width="560">
      <v-card style="border-radius: 18px;">
        <v-card-title class="text-h6 font-weight-bold">
          Rezervēt nodarbību
        </v-card-title>

        <v-card-text>
          <v-text-field
            v-model="form.name"
            label="Tavs vārds"
            variant="solo"
            density="comfortable"
          />
          <v-text-field
            v-model="form.email"
            label="E-pasts"
            variant="solo"
            density="comfortable"
          />

          <div class="mt-2 mb-2" style="opacity:.75; font-size: 13px;">
            Izvēlies datumu. Pilnībā aizņemtas dienas nav izvēlamas.
          </div>

          <v-date-picker
            v-model="form.date"
            :allowed-dates="allowedDates"
            class="mb-4"
          />

          <v-select
            v-model="form.time"
            :items="timeOptions"
            label="Laiks"
            variant="solo"
            density="comfortable"
            :disabled="!form.date"
            :hint="form.date ? 'Izvēlies brīvu laiku' : 'Vispirms izvēlies datumu'"
            persistent-hint
          />

          <div v-if="selectedDateStr && busyTimesForSelectedDay.length" class="mt-3">
            <div style="opacity:.75; font-size: 13px; margin-bottom: 8px;">
              Aizņemti laiki šajā dienā:
            </div>
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

          <v-textarea
            v-model="form.message"
            label="Ziņa (nav obligāti)"
            variant="solo"
            density="comfortable"
            class="mt-4"
          />

          <v-alert
            v-if="submitError"
            type="error"
            variant="tonal"
            class="mt-3"
            :text="submitError"
          />
          <v-alert
            v-if="submitOk"
            type="success"
            variant="tonal"
            class="mt-3"
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
            :disabled="!canSubmit"
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
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'

const route = useRoute()

// Trainer
const trainer = ref({ id: null, name: '', sport: '', description: '' })
const loading = ref(true)
const error = ref('')

const crumbs = computed(() => [
  { title: 'Sākums', to: '/' },
  { title: 'Treneri', to: '/treneri' },
  { title: 'Profils', disabled: true },
])

const photoSrc = computed(() => `/trainers/${route.params.id}.jpg`)

// Booking state
const bookingDialog = ref(false)
const submitting = ref(false)
const submitError = ref('')
const submitOk = ref(false)

// Calendar busy data: [{id,date,time}]
const busy = ref([])

// Form
const form = ref({
  name: '',
  email: '',
  date: null,   // v-date-picker value
  time: '',
  message: '',
})

/** Convert v-date-picker value into YYYY-MM-DD */
function dateStr(v) {
  if (!v) return ''
  if (typeof v === 'string') return v.slice(0, 10)
  return new Date(v).toISOString().slice(0, 10)
}

const selectedDateStr = computed(() => dateStr(form.value.date))

// Create time slots (09:00–19:00, every 60 minutes)
const allTimes = Array.from({ length: 11 }, (_, i) => {
  const h = 9 + i
  return `${String(h).padStart(2, '0')}:00`
})

// Busy times for selected date
const busyTimesForSelectedDay = computed(() => {
  const d = selectedDateStr.value
  if (!d) return []

  const times = busy.value
    .filter(x => x.date === d)
    .map(x => (x.time || '').slice(0, 5)) // HH:MM

  // unique + sort
  return Array.from(new Set(times)).sort()
})
// Available time options for selected date
const timeOptions = computed(() => {
  if (!selectedDateStr.value) return allTimes
  const busySet = new Set(busyTimesForSelectedDay.value)
  return allTimes.filter(t => !busySet.has(t))
})

// Disable fully-booked days (if allTimes are taken)
const allowedDates = (val) => {
  const d = dateStr(val)
  if (!d) return true
  const count = busy.value.filter(x => x.date === d).length
  return count < allTimes.length
}

const canSubmit = computed(() => {
  return (
    form.value.name.trim().length > 0 &&
    form.value.email.trim().length > 0 &&
    !!selectedDateStr.value &&
    form.value.time.trim().length > 0
  )
})

onMounted(async () => {
  loading.value = true
  error.value = ''

  try {
    const { data } = await api.get(`/treneri/${route.params.id}`)
    trainer.value = data
  } catch (e) {
    error.value = 'Pārbaudi, vai darbojas Laravel serveris un API maršruti.'
  } finally {
    loading.value = false
  }
})

function contact() {
  alert('Demo: šeit būs saziņas forma vai čats.')
}

async function loadBusy() {
  // Load busy slots for next 30 days
  const from = new Date()
  const to = new Date()
  to.setDate(to.getDate() + 30)

  const fmt = (d) => d.toISOString().slice(0, 10)

  const { data } = await api.get(`/trainers/${route.params.id}/bookings`, {
    params: { from: fmt(from), to: fmt(to) },
  })

  busy.value = Array.isArray(data) ? data : []
}

async function openBooking() {
  submitError.value = ''
  submitOk.value = false
  bookingDialog.value = true

  try {
    await loadBusy()
  } catch (e) {
    // If endpoint not added yet
    submitError.value = 'Neizdevās ielādēt aizņemtos laikus. Pārbaudi API /trainers/{id}/bookings.'
  }
}

async function submitBooking() {
  
  submitting.value = true
  submitError.value = ''
  submitOk.value = false

  try {
    await api.post('/bookings', {
      trainer_id: Number(route.params.id),
      client_name: form.value.name,
      client_email: form.value.email,
      date: selectedDateStr.value,
      time: form.value.time,
      message: form.value.message,
    })

    submitOk.value = true

    // refresh busy slots after booking
    await loadBusy()

    // optional: clear message
    form.value.message = ''
  } catch (e) {
    submitError.value = 'Neizdevās nosūtīt rezervāciju. Pārbaudi ievadītos datus.'
  } finally {
    submitting.value = false
  }
}
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
    radial-gradient(circle at 20% 20%, color-mix(in srgb, var(--v-theme-primary) 24%, transparent), transparent 45%),
    radial-gradient(circle at 80% 30%, color-mix(in srgb, var(--v-theme-secondary) 18%, transparent), transparent 45%),
    radial-gradient(circle at 50% 90%, color-mix(in srgb, var(--v-theme-accent) 14%, transparent), transparent 55%);
  opacity: 0.9;
}

.shadow {
  box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
}

.sideCard {
  border-radius: 22px;
  padding: 20px;
  background: color-mix(in srgb, var(--v-theme-surface) 86%, transparent);
  border: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
}

.infoRow {
  display: flex;
  align-items: center;
  gap: 10px;
  opacity: 0.85;
  margin-bottom: 10px;
}

.btn {
  border-radius: 16px;
  font-weight: 900;
}

.muted {
  opacity: 0.85;
}
</style>