<template>
  <v-app>
    <!-- MOBILE DRAWER -->
    <v-navigation-drawer
      v-model="drawer"
      location="left"
      temporary
      width="280"
    >
      <div class="pa-4 d-flex align-center ga-3">
        <v-avatar color="primary" size="34">
          <v-icon icon="mdi-dumbbell" />
        </v-avatar>
        <div class="font-weight-black">FitCoach</div>
      </div>

      <v-divider />

      <v-list nav density="comfortable">
        <v-list-item
          title="Sākums"
          prepend-icon="mdi-home"
          @click="go('/')"
        />
        <v-list-item
          title="Treneri"
          prepend-icon="mdi-account-group"
          @click="go('/treneri')"
        />

        <v-list-item
          v-if="isLoggedIn"
          title="Manas rezervācijas"
          prepend-icon="mdi-calendar-check"
          @click="go('/manas-rezervacijas')"
        />

        <v-list-item
          v-if="isAdmin"
          title="Admin panelis"
          prepend-icon="mdi-shield-account"
          @click="go('/admin')"
        />
      </v-list>

      <v-divider class="my-2" />

      <div class="pa-4 d-flex flex-column ga-2">
        <v-btn
          v-if="!isLoggedIn"
          color="primary"
          variant="tonal"
          prepend-icon="mdi-login"
          @click="go('/login')"
        >
          Ienākt
        </v-btn>

        <v-btn
          v-if="!isLoggedIn"
          color="primary"
          variant="flat"
          prepend-icon="mdi-account-plus"
          @click="go('/register')"
        >
          Reģistrēties
        </v-btn>

        <v-btn
          v-if="isLoggedIn"
          variant="tonal"
          color="primary"
          @click="logout"
        >
          Iziet
        </v-btn>

        <v-btn
          :prepend-icon="isDark ? 'mdi-weather-night' : 'mdi-white-balance-sunny'"
          variant="tonal"
          color="primary"
          @click="toggleTheme"
        >
          Tēma
        </v-btn>
      </div>
    </v-navigation-drawer>

    <!-- TOP BAR -->
    <v-app-bar flat class="appbar">
      <v-container class="d-flex align-center justify-space-between">
        <div class="d-flex align-center ga-2">
          <!-- BURGER only on mobile -->
          <v-btn
            icon="mdi-menu"
            variant="text"
            class="d-sm-none"
            @click="drawer = true"
          />

          <v-avatar color="primary" size="34" class="ml-sm-0">
            <v-icon icon="mdi-dumbbell" />
          </v-avatar>

          <div class="brand" @click="go('/')">FitCoach</div>

          <!-- DESKTOP NAV -->
          <div class="d-none d-sm-flex align-center ga-1 ml-2">
            <v-btn v-if="isLoggedIn" variant="text" @click="go('/treneri')">Treneri</v-btn>
            <v-btn v-if="isLoggedIn" variant="text" @click="go('/manas-rezervacijas')">
              Manas rezervācijas
            </v-btn>
            <v-btn v-if="isAdmin" variant="text" @click="go('/admin')">Admin</v-btn>
            <v-btn v-if="isAdmin" variant="text" @click="go('/statistika')">Statistika</v-btn>
          </div>
        </div>

        <div class="d-flex align-center ga-2">
          <!-- DESKTOP AUTH BUTTONS -->
          <template v-if="!isLoggedIn">
            <v-btn v-if="!isLoggedIn" variant="text" @click="go('/login')">Ienākt</v-btn>
            <v-btn v-if="!isLoggedIn" variant="text" @click="go('/register')">Reģistrēties</v-btn>
          </template>

          <v-btn
            v-if="isLoggedIn"
            class="d-none d-sm-inline-flex"
            variant="tonal"
            @click="logout"
          >
            Iziet
          </v-btn>

          <v-btn
            :icon="isDark ? 'mdi-weather-night' : 'mdi-white-balance-sunny'"
            variant="tonal"
            color="primary"
            @click="toggleTheme"
          />
        </div>
      </v-container>
    </v-app-bar>

    <v-main>
      <router-view />
    </v-main>

    
  </v-app>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useTheme } from 'vuetify'
import api from './services/api'
import { isLoggedIn, isAdmin, clearAuth } from './services/auth'

const router = useRouter()
const theme = useTheme()

const drawer = ref(false)
const isDark = computed(() => theme.global.name.value === 'dark')

async function logout () {
  try { await api.post('/auth/logout') } catch (e) {}
  clearAuth()
  drawer.value = false
  router.push('/login')
}

function go(path) {
  drawer.value = false
  router.push(path)
}
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
  user-select: none;
  margin-left: 8px;
}

.footer {
  background: color-mix(in srgb, var(--v-theme-surface) 92%, transparent);
  border-top: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
}
</style>