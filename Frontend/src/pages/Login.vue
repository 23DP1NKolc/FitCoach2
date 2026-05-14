<template>
  <v-container class="py-10">
    <v-row justify="center">
      <v-col cols="12" sm="8" md="5" lg="4">
        <v-card class="pa-6 rounded-xl" elevation="0" style="border:1px solid rgba(0,0,0,.08)">
          <div class="text-h5 font-weight-black mb-6 text-center">Pieslēgties</div>

          <v-alert
            v-if="err"
            type="error"
            variant="tonal"
            class="rounded-xl mb-4"
            :text="err"
          />

          <v-text-field
            v-model="form.email"
            label="E-pasts"
            prepend-inner-icon="mdi-email"
            variant="solo"
            density="comfortable"
            autocomplete="email"
          />

          <v-text-field
            v-model="form.password"
            label="Parole"
            prepend-inner-icon="mdi-lock"
            variant="solo"
            density="comfortable"
            type="password"
            autocomplete="current-password"
            class="mt-2"
          />

          <v-btn
            color="primary"
            variant="flat"
            block
            class="mt-4"
            :loading="loading"
            @click="login"
          >
            Ienākt
          </v-btn>

          <div class="text-body-2 mt-4 text-center" style="opacity:.75">
            Nav konta?
            <a href="" @click.prevent="router.push('/register')">Reģistrēties</a>
          </div>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'
import { setAuth } from '../services/auth'

const router = useRouter()

const loading = ref(false)
const err = ref('')

const form = ref({
  email: '',
  password: '',
})

async function login() {
  loading.value = true
  err.value = ''

  try {
    const { data } = await api.post('/auth/login', {
      email: form.value.email,
      password: form.value.password,
    })

    // saglabā token + user un uzreiz atjauno App.vue
    setAuth(data.token, data.user)
    const redirect = router.currentRoute.value.query.redirect
router.push(redirect ? String(redirect) : (data.user?.role === 'admin' ? '/admin' : '/'))

    router.push(data.user?.role === 'admin' ? '/admin' : '/')
  } catch (e) {
    err.value = e?.response?.data?.message || 'Nepareizs e-pasts vai parole.'
  } finally {
    loading.value = false
  }
}
</script>