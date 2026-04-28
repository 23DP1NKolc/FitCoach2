<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Trainer;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        $trainers = [
            ['name' => 'Jānis Ozols', 'sport' => 'Fitness', 'description' => 'Sertificēts fitnesa treneris ar 5 gadu pieredzi.'],
            ['name' => 'Anna Kalniņa', 'sport' => 'Joga', 'description' => 'Jogas trenere ar holistisku pieeju un pieredzi grupu nodarbībās.'],
            ['name' => 'Mārtiņš Liepa', 'sport' => 'Kardio', 'description' => 'Kardio un izturības treniņi, programmas svara samazināšanai.'],
            ['name' => 'Elīna Bērziņa', 'sport' => 'Pilates', 'description' => 'Pilates nodarbības mugurai, stājai un ķermeņa kontrolei.'],
            ['name' => 'Rihards Vītols', 'sport' => 'Bokss', 'description' => 'Boksa tehnika un fiziskā sagatavotība iesācējiem un pieredzējušiem.'],
            ['name' => 'Līga Siliņa', 'sport' => 'Dejas', 'description' => 'Deju treniņi koordinācijai, ritmam un fiziskajai formai.'],
            ['name' => 'Andris Zariņš', 'sport' => 'Futbols', 'description' => 'Individuālie futbola treniņi tehnikai un ātrumam.'],
            ['name' => 'Kristīne Ozoliņa', 'sport' => 'Peldēšana', 'description' => 'Peldēšanas tehnika un elpošanas uzlabošana dažādiem līmeņiem.'],
            ['name' => 'Toms Grīnbergs', 'sport' => 'Spēka treniņi', 'description' => 'Spēka programmas muskuļu masas palielināšanai un tehnikas uzlabošanai.'],
            ['name' => 'Dace Krūmiņa', 'sport' => 'Stiepšanās', 'description' => 'Mobilitāte, lokanība un stiepšanās pēc individuāla plāna.'],
            ['name' => 'Artūrs Mežs', 'sport' => 'CrossFit', 'description' => 'Funkcionālie treniņi ar progresiju un drošu slodzes kontroli.'],
            ['name' => 'Sabīne Puriņa', 'sport' => 'Skriešana', 'description' => 'Skriešanas tehnikas uzlabošana, sagatavošanās sacensībām.'],
        ];

        foreach ($trainers as $t) {
            Trainer::create($t);
        }
    }
}