# FitCoach

FitCoach ir mācību projekts – platforma treneru meklēšanai dažādos sporta veidos (fitness, joga, dejas u.c.). Projekts sastāv no Frontend (Vue.js + Vuetify) un Backend (Laravel) daļas, kā arī ir savienots ar datubāzi (SQLite).

## Funkcionalitāte (MVP)
- Home page (Vuetify dizains)
- Light/Dark mode pārslēgšana (Vuetify tēmas)
- Treneru saraksts (dati no API)
- Trenera profila lapa (dati no API)
- Datubāze (SQLite) + migrācijas

## Tehnoloģijas
Frontend:
- Vue 3
- Vuetify 3
- Vite
- Axios
- Material Design Icons (MDI)

Backend:
- Laravel (12.x)
- SQLite
- REST API

## Projekta struktūra
FitCoach/
Frontend/ # Vue + Vuetify
Backend/ # Laravel API + DB


## Prasības (pirms palaišanas)
- Node.js (LTS ieteicams) + npm
- PHP 8.x
- Composer

## Palaišana lokāli

### 1) Backend (Laravel)
Atver termināli mapē `Backend` un izpildi:

```bash
cd Backend
composer install
Izveido .env (ja nav):

copy .env.example .env
Ģenerē atslēgu:

php artisan key:generate
Pārliecinies .env, ka ir:

APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
Palaid migrācijas (Laravel piedāvās izveidot database/database.sqlite, ja fails neeksistē):

php artisan migrate
Palaid serveri:

php artisan serve
Backend adrese:

http://127.0.0.1:8000

2) Frontend (Vue + Vuetify)
Atver jaunu termināli mapē Frontend un izpildi:

cd Frontend
npm install
npm run dev
Frontend adrese:

http://localhost:5173

API (Backend)
Pieejamie endpointi:

GET http://127.0.0.1:8000/api/treneri

GET http://127.0.0.1:8000/api/treneri/{id}

Testa dati (Treneri)
Lai pievienotu testa trenerus, palaid Tinker:

cd Backend
php artisan tinker
Tinker konsolē izpildi:

\App\Models\Trainer::create([
  'name' => 'Jānis Ozols',
  'sport' => 'Fitness',
  'description' => 'Sertificēts fitnesa treneris ar 5 gadu pieredzi.'
]);

\App\Models\Trainer::create([
  'name' => 'Anna Kalniņa',
  'sport' => 'Joga',
  'description' => 'Jogas trenere ar holistisku pieeju un pieredzi grupu nodarbībās.'
]);
Pārbaude:

Atver http://127.0.0.1:8000/api/treneri un pārliecinies, ka redzams JSON ar datiem.

Ikonas (MDI) un Vuetify
Projektā tiek izmantotas Material Design Icons. Ja ikonas nerādās, pārliecinies, ka Frontend mapē ir instalēts:

cd Frontend
npm i @mdi/font
Un src/plugins/vuetify.js ir:

import '@mdi/font/css/materialdesignicons.css'
Biežākās problēmas
404 uz /api/...
Pārliecinies, ka Laravel serveris darbojas (php artisan serve) un maršruti ir reģistrēti:

php artisan route:list
Failed to resolve import "axios"
Uzinstalē axios Frontend mapē:

cd Frontend
npm install axios