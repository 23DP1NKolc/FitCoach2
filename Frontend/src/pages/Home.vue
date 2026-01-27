<template>
  <div class="page">
    <!-- HERO -->
    <section class="hero">
      <v-container fluid class="heroFluid">
        <div class="content">
          <v-row align="center" class="heroRow">
            <!-- LEFT -->
            <v-col cols="12" md="6">
              <div class="badge mb-5">
                <v-icon icon="mdi-sparkles" class="mr-2" />
                Atrodi treneri ātri un droši
              </div>

              <h1 class="title">
                Atrodi savu ideālo treneri
                <span class="grad">jebkuram sporta veidam</span>
              </h1>

              <p class="subtitle">
                FitCoach palīdz atrast profesionālus trenerus fitnesā, jogā, dejās un citur.
                Izvēlies sporta veidu, apskati profilu un sazinies.
              </p>

              <div class="d-flex flex-wrap ga-3 mt-7">
                <v-btn color="primary" size="x-large" class="btn" @click="$router.push('/treneri')">
                  Sākt meklēšanu
                  <v-icon icon="mdi-arrow-right" end />
                </v-btn>

                <v-btn color="secondary" variant="tonal" size="x-large" class="btn" @click="scrollToFeatures">
                  Kā tas strādā
                  <v-icon icon="mdi-play-circle" end />
                </v-btn>
              </div>

              <div class="stats mt-9">
                <div class="stat">
                  <div class="statNum">Ātri</div>
                  <div class="statLbl">atrast treneri</div>
                </div>
                <div class="stat">
                  <div class="statNum">Droši</div>
                  <div class="statLbl">pārbaudāmi profili</div>
                </div>
                <div class="stat">
                  <div class="statNum">Viegli</div>
                  <div class="statLbl">pārskatāma izvēle</div>
                </div>
              </div>
            </v-col>

            <!-- RIGHT -->
            <v-col cols="12" md="6" class="d-flex justify-center">
              <div class="heroCard">
                <div class="orb orb1" />
                <div class="orb orb2" />

                <div class="heroInner">
                  <div class="d-flex align-center ga-3 mb-4">
                    <v-avatar size="48" color="primary">
                      <v-icon icon="mdi-dumbbell" />
                    </v-avatar>

                    <div>
                      <div class="cardTitle">Trenera meklēšana</div>
                      <div class="cardSub muted">Izvēlies sporta veidu un apskati trenerus</div>
                    </div>
                  </div>

                  <div class="chipRow">
                    <v-chip color="secondary" variant="tonal">Fitness</v-chip>
                    <v-chip color="secondary" variant="tonal">Joga</v-chip>
                    <v-chip color="secondary" variant="tonal">Dejas</v-chip>
                  </div>

                  <v-divider class="my-6" />

                  <div class="d-flex ga-3">
                    <v-btn color="primary" variant="flat" class="btnSmall" @click="$router.push('/treneri')">
                      Skatīt trenerus
                    </v-btn>
                    <v-btn color="accent" variant="tonal" class="btnSmall" @click="$router.push('/treneri/1')">
                      Profila piemērs
                    </v-btn>
                  </div>
                </div>
              </div>
            </v-col>
          </v-row>
        </div>
      </v-container>
    </section>

    <!-- FEATURES -->
    <v-container fluid ref="featuresRef" class="featuresFluid">
      <div class="content">
        <div class="sectionHead mb-6">
          <h2 class="sectionTitle">Kā tas strādā</h2>
          <p class="muted">Vienkāršs process: izvēlies, apskati, sazinies.</p>
        </div>

        <v-row>
          <v-col cols="12" md="4" v-for="item in features" :key="item.title">
            <v-card class="feature" elevation="0">
              <v-icon :icon="item.icon" size="46" color="primary" class="mb-3" />
              <h3 class="mb-2">{{ item.title }}</h3>
              <p class="muted">{{ item.text }}</p>
            </v-card>
          </v-col>
        </v-row>
      </div>
    </v-container>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const featuresRef = ref(null)

function scrollToFeatures () {
  // Vuetify v-container ref ir komponents, tāpēc vajag $el
  featuresRef.value?.$el?.scrollIntoView?.({ behavior: 'smooth', block: 'start' })
}

const features = [
  { title: 'Plaša izvēle', text: 'Treneri dažādos sporta veidos vienuviet.', icon: 'mdi-account-group' },
  { title: 'Ērta meklēšana', text: 'Filtri un skaidra kartīšu sistēma.', icon: 'mdi-magnify' },
  { title: 'Droša platforma', text: 'Pārskatāmi profili saziņai ar treneri.', icon: 'mdi-shield-check' },
]
</script>

<style scoped>
.page {
  width: 100%;
}

/* HERO background */
.hero {
  background: radial-gradient(1000px 600px at 20% 0%, rgba(109,40,217,0.18), transparent 60%),
              radial-gradient(900px 600px at 85% 20%, rgba(34,211,238,0.14), transparent 55%),
              linear-gradient(180deg, color-mix(in srgb, var(--v-theme-background) 92%, #fff), var(--v-theme-background));
  padding: 56px 0 44px;
}

/* FLUID wrappers */
.heroFluid,
.featuresFluid {
  padding-left: 24px;
  padding-right: 24px;
}

/* Center + max width */
.content {
  max-width: 1150px;
  margin: 0 auto;
}

.heroRow {
  min-height: calc(70vh - 64px);
}

/* Badge + typography */
.badge {
  display: inline-flex;
  align-items: center;
  padding: 9px 14px;
  border-radius: 999px;
  background: color-mix(in srgb, var(--v-theme-primary) 14%, transparent);
  border: 1px solid color-mix(in srgb, var(--v-theme-primary) 28%, transparent);
  font-weight: 700;
}

.title {
  font-size: clamp(40px, 4.2vw, 56px);
  font-weight: 950;
  line-height: 1.05;
  margin: 6px 0 18px 0;
  letter-spacing: -0.5px;
  text-align: left;
}

.grad {
  display: block;
  margin-top: 8px;
  background: linear-gradient(90deg, var(--v-theme-primary), var(--v-theme-secondary));
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}

.subtitle {
  font-size: 18px;
  max-width: 560px;
  opacity: 0.85;
  margin-top: 10px;
  text-align: left;
}

/* Buttons */
.btn {
  border-radius: 16px;
  font-weight: 900;
}

/* Stats */
.stats {
  display: flex;
  gap: 14px;
  flex-wrap: wrap;
}

.stat {
  padding: 12px 14px;
  border-radius: 16px;
  background: color-mix(in srgb, var(--v-theme-surface) 78%, transparent);
  border: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
  min-width: 150px;
}

.statNum {
  font-weight: 950;
  font-size: 18px;
}

.statLbl {
  opacity: 0.7;
  font-size: 13px;
}

/* Hero card */
.heroCard {
  position: relative;
  width: min(520px, 100%);
  border-radius: 26px;
  padding: 26px;
  overflow: hidden;
  background: color-mix(in srgb, var(--v-theme-surface) 82%, transparent);
  border: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
  box-shadow: 0 20px 55px rgba(0,0,0,0.16);
}

.heroInner { position: relative; z-index: 2; }

.orb {
  position: absolute;
  width: 260px;
  height: 260px;
  border-radius: 50%;
  filter: blur(26px);
  opacity: 0.55;
  z-index: 1;
}
.orb1 { background: var(--v-theme-primary); top: -110px; right: -110px; }
.orb2 { background: var(--v-theme-secondary); bottom: -110px; left: -110px; }

.cardTitle { font-weight: 950; font-size: 18px; }
.cardSub { font-size: 13px; }

.chipRow {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}

.btnSmall {
  border-radius: 14px;
  font-weight: 900;
}

/* FEATURES */
.featuresFluid {
  padding-top: 44px;
  padding-bottom: 70px;
}

.sectionHead {
  max-width: 640px;
}

.sectionTitle {
  font-size: 28px;
  font-weight: 950;
  margin: 0 0 8px 0;
}

.feature {
  padding: 26px;
  border-radius: 20px;
  background: color-mix(in srgb, var(--v-theme-surface) 88%, transparent);
  border: 1px solid color-mix(in srgb, var(--v-theme-on-surface) 10%, transparent);
}

/* Helpers */
.muted { opacity: 0.75; }

/* Mobile tweaks */
@media (max-width: 960px) {
  .title, .subtitle { text-align: center; }
  .stats { justify-content: center; }
}
</style>
