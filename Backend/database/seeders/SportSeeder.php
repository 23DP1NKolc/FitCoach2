<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        $sports = ['Fitness', 'Joga', 'Dejas', 'Kardio', 'Pilates', 'Bokss'];

        foreach ($sports as $name) {
            Sport::firstOrCreate(['name' => $name]);
        }
    }
}