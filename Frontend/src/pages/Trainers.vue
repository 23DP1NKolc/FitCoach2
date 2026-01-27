<template>
  <v-app>
    <v-main>
      <v-container>

        <h1 class="text-h4 font-weight-bold mb-6">
          Treneri
        </h1>

        <v-row>
          <v-col
            cols="12"
            md="4"
            v-for="trainer in trainers"
            :key="trainer.id"
          >
            <v-card elevation="2" class="pa-4">
              <v-card-title class="text-h6">
                {{ trainer.name }}
              </v-card-title>

              <v-card-subtitle>
                Sporta veids: {{ trainer.sport }}
              </v-card-subtitle>

              <v-card-text>
                {{ trainer.description }}
              </v-card-text>

              <v-card-actions>
                <v-btn
                  color="primary"
                  variant="text"
                  @click="$router.push(`/treneri/${trainer.id}`)"
                >
                  Skatīt profilu
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>

      </v-container>
    </v-main>
  </v-app>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const trainers = ref([])

onMounted(async () => {
  const response = await api.get('/treneri')
  trainers.value = response.data
})
</script>
