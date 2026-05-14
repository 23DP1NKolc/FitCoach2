<template>
  <div class="homePage page">
    <section class="hero">
      <v-container fluid class="fluid">
        <div class="content">
          <v-row align="center" class="heroRow">
            <v-col cols="12" md="7">
              <div class="badge mb-5">
                <v-icon icon="mdi-sparkles" class="mr-2" />
                FitCoach – treneru meklēšana vienuviet
              </div>

              <h1 class="title">
                Atrodi savu ideālo treneri
                <span class="grad">ātri, droši un ērti</span>
              </h1>

              <p class="subtitle">
                Izvēlies sporta veidu, apskati trenera profilu un sāc trenēties.
              </p>

              <div class="cta mt-6 d-flex flex-wrap ga-3">
                <v-btn color="primary" size="x-large" class="btn" @click="goTrainers()">
                  Skatīt trenerus
                  <v-icon icon="mdi-arrow-right" end />
                </v-btn>

                

                
                

                <v-btn color="secondary" variant="tonal" size="x-large" class="btn" @click="scrollTo('how')">
                  Kā tas strādā
                  <v-icon icon="mdi-play-circle" end />
                </v-btn>
              </div>
            </v-col>

            <v-col cols="12" md="5" class="d-flex justify-center">
              <div class="glass">
                <div class="orb orb1"></div>
                <div class="orb orb2"></div>

                <div class="glassInner">
                  <div class="d-flex align-center ga-3 mb-5">
                    <v-avatar size="50" color="primary">
                      <v-icon icon="mdi-dumbbell" />
                    </v-avatar>
                    <div>
                      <div class="cardTitle">Top treneri šonedēļ</div>
                      <div class="muted">Ieteikumi (demo)</div>
                    </div>
                  </div>

                  <div class="miniList">
                    <div class="miniItem" v-for="t in topDemo" :key="t.name">
                      <div class="miniLeft">
                        <v-icon icon="mdi-account" class="mr-2" />
                        <div>
                          <div class="miniName">{{ t.name }}</div>
                          <div class="miniMeta muted">{{ t.sport }}</div>
                        </div>
                      </div>
                      <v-chip size="small" color="accent" variant="tonal">4.9</v-chip>
                    </div>
                  </div>

                  <v-divider class="my-5" />

                  <div class="d-flex ga-3">
                    <v-btn color="primary" variant="flat" class="btnSmall" @click="goTrainers()">
                      Atvērt sarakstu
                    </v-btn>
                    
                  </div>

                  <div v-if="!isLoggedIn" class="text-caption mt-4" style="opacity:.7">
                    Lai skatītu trenerus, nepieciešams ielogoties.
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>
        </div>
      </v-container>
    </section>

    <v-container fluid class="fluid" id="how">
      <div class="content section">
        <div class="sectionHead mb-7">
          <h2 class="sectionTitle">Kā tas strādā</h2>
          <p class="muted">Trīs vienkārši soļi līdz pirmajam treniņam.</p>
        </div>

        <v-row>
          <v-col cols="12" md="4" v-for="f in features" :key="f.title">
            <v-card class="feature" elevation="0">
              <v-icon :icon="f.icon" size="44" color="primary" class="mb-3" />
              <h3 class="mb-2">{{ f.title }}</h3>
              <p class="muted mb-0">{{ f.text }}</p>
            </v-card>
          </v-col>
        </v-row>
      </div>
    </v-container>

    <v-container fluid class="fluid">
      <div class="content section">
        <v-card class="finalCta" elevation="0">
          <div class="finalBg"></div>
          <div class="finalInner">
            <div>
              <h2 class="finalTitle">Gatavs sākt?</h2>
              <p class="muted mb-0">Atver treneru sarakstu un izvēlies sev piemērotāko.</p>
            </div>
            <v-btn color="primary" size="x-large" class="btn" @click="goTrainers()">
              Sākt tagad
              <v-icon icon="mdi-arrow-right" end />
            </v-btn>
          </div>
        </v-card>
      </div>
    </v-container>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { isLoggedIn } from '../services/auth'
import '../assets/home.css'

const router = useRouter()

const categories = ['Fitness', 'Joga', 'Dejas', 'Kardio', 'Pilates']

const topDemo = [
  { name: 'Jānis Ozols', sport: 'Fitness' },
  { name: 'Anna Kalniņa', sport: 'Joga' },
  { name: 'Mārtiņš Liepa', sport: 'Kardio' },
]

const features = [
  { title: 'Izvēlies', text: 'Atrodi sporta veidu un treneri sarakstā.', icon: 'mdi-magnify' },
  { title: 'Apskati profilu', text: 'Skaties aprakstu un galveno informāciju.', icon: 'mdi-account-box' },
  { title: 'Sazinies', text: 'Sāc saziņu un rezervē nodarbību (nākotnē).', icon: 'mdi-message' },
]

function goTrainers() {
  if (!isLoggedIn.value) {
    router.push({ path: '/login', query: { redirect: '/treneri' } })
    return
  }
  router.push('/treneri')
}

function goSport(name) {
  if (!isLoggedIn.value) {
    router.push({ path: '/login', query: { redirect: `/treneri?q=${encodeURIComponent(name)}` } })
    return
  }
  router.push({ path: '/treneri', query: { q: name } })
}

function scrollTo(id) {
  document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}
</script>