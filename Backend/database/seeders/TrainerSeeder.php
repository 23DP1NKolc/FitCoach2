<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainer;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Pieņemam, ka SportSeeder ieliek sportus šādā secībā (id):
        // 1 Fitness, 2 Joga, 3 Dejas, 4 Kardio, 5 Pilates, 6 Bokss
        Trainer::insert([
            [
                'name' => 'Jānis Ozols',
                'description' => 'Sertificēts fitnesa treneris ar 5 gadu pieredzi. Spēka treniņi, tehnika un plāni iesācējiem.',
                'sport' => 'Fitness',
                'sport_id' => 1,
                'city' => 'Rīga',
                'price_per_hour' => 20,
                'online' => 1,
                'gender' => 'male',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Anna Kalniņa',
                'description' => 'Jogas trenere ar holistisku pieeju un pieredzi grupu nodarbībās. Elpošana, mobilitāte, relax.',
                'sport' => 'Joga',
                'sport_id' => 2,
                'city' => 'Rīga',
                'price_per_hour' => 25,
                'online' => 1,
                'gender' => 'female',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mārtiņš Liepa',
                'description' => 'Kardio un izturības treniņi, programmas svara samazināšanai. Drošs progress un motivācija.',
                'sport' => 'Kardio',
                'sport_id' => 4,
                'city' => 'Daugavpils',
                'price_per_hour' => 18,
                'online' => 0,
                'gender' => 'male',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Elīna Bērziņa',
                'description' => 'Pilates nodarbības mugurai, stājai un ķermeņa kontrolei. Der arī pēc traumām (viegls režīms).',
                'sport' => 'Pilates',
                'sport_id' => 5,
                'city' => 'Liepāja',
                'price_per_hour' => 22,
                'online' => 1,
                'gender' => 'female',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Viktorija Kozireva',
                'description' => 'Deju fitnesa nodarbības – ritms, kardio un prieks. Piemērots iesācējiem un grupām.',
                'sport' => 'Dejas',
                'sport_id' => 3,
                'city' => 'Ventspils',
                'price_per_hour' => 20,
                'online' => 0,
                'gender' => 'female',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Nikita Kolcovs',
                'description' => 'Fitness + kardio plāni, treniņi zālē un ārā. Uzsvars uz disciplīnu un rezultātu.',
                'sport' => 'Fitness',
                'sport_id' => 1,
                'city' => 'Rīga',
                'price_per_hour' => 30,
                'online' => 1,
                'gender' => 'male',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Rihards Vītols',
                'description' => 'Boksa pamati un fiziskā sagatavotība. Darbs ar tehniku, koordināciju un izturību.',
                'sport' => 'Bokss',
                'sport_id' => 6,
                'city' => 'Jelgava',
                'price_per_hour' => 25,
                'online' => 0,
                'gender' => 'male',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Laura Priedīte',
                'description' => 'Joga iesācējiem un stiepšanās. Palīdz atslābināt muguru un uzlabot pašsajūtu.',
                'sport' => 'Joga',
                'sport_id' => 2,
                'city' => 'Valmiera',
                'price_per_hour' => 18,
                'online' => 1,
                'gender' => 'female',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Andris Šmits',
                'description' => 'Kardio treniņi un skriešanas programma. Sagatavošana 5K/10K, pulss un slodze.',
                'sport' => 'Kardio',
                'sport_id' => 4,
                'city' => 'Rīga',
                'price_per_hour' => 22,
                'online' => 1,
                'gender' => 'male',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Santa Ozoliņa',
                'description' => 'Pilates un mobilitāte. Uzsvars uz dziļajiem muskuļiem un kustību kvalitāti.',
                'sport' => 'Pilates',
                'sport_id' => 5,
                'city' => 'Rēzekne',
                'price_per_hour' => 19,
                'online' => 0,
                'gender' => 'female',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}