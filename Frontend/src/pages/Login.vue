<template>
  <v-container class="py-10" style="max-width: 520px;">
    <v-card class="pa-8 rounded-xl" elevation="0">
      <h1 class="text-h5 font-weight-black text-center mb-6">Pieslēgties</h1>

      <v-alert
        v-if="err"
        type="error"
        variant="tonal"
        class="rounded-xl mb-4"
        :text="err"
      />

      <v-form @submit.prevent="login">
        <v-text-field
          v-model="form.email"
          label="E-pasts"
          prepend-inner-icon="mdi-email"
          variant="solo"
          density="comfortable"
          autocomplete="email"
          :disabled="loading"
          class="mb-3"
          required
        />

        <v-text-field
          v-model="form.password"
          label="Parole"
          prepend-inner-icon="mdi-lock"
          variant="solo"
          density="comfortable"
          autocomplete="current-password"
          :type="show ? 'text' : 'password'"
          :append-inner-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
          @click:append-inner="show = !show"
          :disabled="loading"
          class="mb-4"
          required
        />

        <v-btn
          type="submit"
          color="primary"
          size="large"
          block
          :loading="loading"
          :disabled="loading"
          class="rounded-lg font-weight-black"
        >
          Ienākt
        </v-btn>

        <div class="text-body-2 mt-4 text-center" style="opacity:.8;">
          Nav konta?
          <a href="#" @click.prevent="router.push('/register')">Reģistrēties</a>
        </div>
      </v-form>
    </v-card>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../services/api'
import { setAuth } from '../services/auth' // ja tev ir auth.js ar setAuth()

const router = useRouter()
const route = useRoute()

const loading = ref(false)
const err = ref('')
const show = ref(false)

const form = ref({
  email: '',
  password: '',
})

async function login () {
  err.value = ''

  // elementāra pārbaude, lai nesūtītu tukšus
  if (!form.value.email.trim() || !form.value.password.trim()) {
    err.value = 'Ievadi e-pastu un paroli.'
    return
  }

  loading.value = true
  try {
    const { data } = await api.post('/auth/login', {
      email: form.value.email.trim(),
      password: form.value.password,
    })

    // saglabā token + user un atjauno AppBar uzreiz
    setAuth(data.token, data.user)

    // ja nāca redirect no query (?redirect=/treneri)
    const redirect = route.query.redirect
    router.push(typeof redirect === 'string' ? redirect : (data.user?.role === 'admin' ? '/admin' : '/'))
  } catch (e) {
    err.value =
      e?.response?.data?.message ||
      'Nepareizs e-pasts vai parole.'
  } finally {
    loading.value = false
  }
}
</script>