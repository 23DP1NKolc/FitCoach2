<template>
  <v-app>
    <v-app-bar flat class="appbar">
      <v-container class="d-flex align-center justify-space-between">
        <div class="d-flex align-center ga-3">
          <v-avatar color="primary" size="34">
            <v-icon icon="mdi-dumbbell" />
          </v-avatar>

          <span class="brand" @click="$router.push('/')">FitCoach - treneru meklēšana</span>

        
        </div>

        <div class="d-flex align-center ga-2">
          <v-btn variant="text" @click="$router.push('/treneri')">
            Treneri
          </v-btn>

          <v-btn
            :icon="isDark ? 'mdi-weather-night' : 'mdi-white-balance-sunny'"
            variant="tonal"
            color="primary"
            @click="toggleTheme"
          />

          <v-btn color="primary" variant="flat" class="cta" @click="$router.push('/treneri')">
            Sākt
          </v-btn>
        </div>
      </v-container>
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>

    <v-footer class="footer">
      <v-container class="d-flex align-center ga-2">
        <span>© 2026 FitCoach. Mācību projekts.</span>
      </v-container>
    </v-footer>
  </v-app>
</template>

<script setup>
import { computed } from 'vue'
import { useTheme } from 'vuetify'

const theme = useTheme()
const isDark = computed(() => theme.global.name.value === 'dark')

function toggleTheme () {
  theme.global.name.value = isDark.value ? 'light' : 'dark'
}
</script>

<style scoped>
.appbar {
  backdrop-filter: blur(10px);
  background: color-mix(in srgb, var(--v-theme-surface) 85%, transparent);
  border-bottom: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 12%, transparent);
}

.brand {
  font-weight: 800;
  letter-spacing: 0.2px;
  cursor: pointer;
}

.cta {
  border-radius: 14px;
  font-weight: 700;
}

.footer {
  background: color-mix(in srgb, var(--v-theme-surface) 92%, transparent);
  border-top: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
}

.muted {
  opacity: 0.7;
}
</style>
