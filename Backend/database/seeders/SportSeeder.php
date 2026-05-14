<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sport;

class SportSeeder extends Seeder
{
    public function run(): void
    {
        $items = ['Fitness','Joga','Dejas','Kardio','Pilates','Bokss'];

        foreach ($items as $name) {
            Sport::updateOrCreate(['name' => $name]);
        }
    }
}