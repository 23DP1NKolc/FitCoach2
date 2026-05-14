<template>
  <v-container class="py-10" style="max-width:520px;">
    <v-card class="pa-6" elevation="0" style="border-radius:18px;">
      <h1 class="text-h5 font-weight-black mb-4">Reģistrācija</h1>

      <v-text-field
        v-model="name"
        label="Vārds"
        variant="solo"
        density="comfortable"
        prepend-inner-icon="mdi-account-outline"
      />

      <v-text-field
        v-model="email"
        label="E-pasts"
        variant="solo"
        density="comfortable"
        prepend-inner-icon="mdi-email-outline"
      />

      <v-text-field
        v-model="password"
        label="Parole"
        type="password"
        variant="solo"
        density="comfortable"
        prepend-inner-icon="mdi-lock-outline"
      />

      <v-alert
        v-if="ok"
        type="success"
        variant="tonal"
        class="mb-3"
        text="Konts izveidots! Tagad vari pieslēgties."
      />

      <v-alert
        v-if="err"
        type="error"
        variant="tonal"
        class="mb-3"
        :text="err"
      />

      <v-btn
        color="primary"
        block
        size="large"
        :loading="loading"
        @click="register"
      >
        Izveidot kontu
      </v-btn>

      <div class="mt-4" style="opacity:.8;">
        Ir konts?
        <a href="#" @click.prevent="$router.push('/login')">Pieslēgties</a>
      </div>
    </v-card>
  </v-container>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api'

const router = useRouter()

const name = ref('')
const email = ref('')
const password = ref('')

const loading = ref(false)
const err = ref('')
const ok = ref(false)

async function register() {
  loading.value = true
  err.value = ''
  ok.value = false

  try {
    await api.post('/auth/register', {
      name: name.value,
      email: email.value,
      password: password.value,
    })

    ok.value = true

    // pēc 700ms pārmet uz login
    setTimeout(() => router.push('/login'), 700)
  } catch (e) {
    const msg = e?.response?.data?.message
    const errors = e?.response?.data?.errors
    err.value = msg || (errors ? JSON.stringify(errors) : 'Neizdevās reģistrēties. Pārbaudi datus.')
  } finally {
    loading.value = false
  }
}
</script>