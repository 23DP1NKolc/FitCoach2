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

          <p class="mt-6 text-body-1 muted">
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
              @click="book"
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

            <v-btn block color="primary" variant="flat" class="btn" @click="$router.push('/treneri')">
              Skatīt citus trenerus
            </v-btn>
          </v-card>
        </v-col>
      </v-row>
    </v-card>
  </v-container>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'

const route = useRoute()

const trainer = ref({ name: '', sport: '', description: '' })
const loading = ref(true)
const error = ref('')

const crumbs = computed(() => [
  { title: 'Sākums', to: '/' },
  { title: 'Treneri', to: '/treneri' },
  { title: 'Profils', disabled: true },
])

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

function book() {
  alert('Demo: šeit būs rezervācijas kalendārs.')
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
  box-shadow: 0 18px 40px rgba(0,0,0,0.18);
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