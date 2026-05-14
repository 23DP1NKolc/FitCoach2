<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainer;
use App\Models\Sport;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $fitness = Sport::where('name','Fitness')->value('id');
        $joga = Sport::where('name','Joga')->value('id');
        $kardio = Sport::where('name','Kardio')->value('id');
        $pilates = Sport::where('name','Pilates')->value('id');

        Trainer::updateOrCreate(
            ['name' => 'Jānis Ozols'],
            [
                'sport' => 'Fitness',
                'sport_id' => $fitness,
                'description' => 'Sertificēts fitnesa treneris ar 5 gadu pieredzi.',
                'city' => 'Rīga',
                'price_per_hour' => 20,
                'online' => true,
            ]
        );

        Trainer::updateOrCreate(
            ['name' => 'Anna Kalniņa'],
            [
                'sport' => 'Joga',
                'sport_id' => $joga,
                'description' => 'Jogas trenere ar holistisku pieeju un pieredzi grupu nodarbībās.',
                'city' => 'Rīga',
                'price_per_hour' => 25,
                'online' => true,
            ]
        );

        Trainer::updateOrCreate(
            ['name' => 'Mārtiņš Liepa'],
            [
                'sport' => 'Kardio',
                'sport_id' => $kardio,
                'description' => 'Kardio un izturības treniņi, programmas svara samazināšanai.',
                'city' => 'Daugavpils',
                'price_per_hour' => 18,
                'online' => true,
            ]
        );

        Trainer::updateOrCreate(
            ['name' => 'Elīna Bērziņa'],
            [
                'sport' => 'Pilates',
                'sport_id' => $pilates,
                'description' => 'Pilates nodarbības mugurai, stājai un ķermeņa kontrolei.',
                'city' => 'Liepāja',
                'price_per_hour' => 22,
                'online' => false,
            ]
        );
    }
}