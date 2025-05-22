<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EcoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ecoles')->insert([
            [
                'nom' => 'Ancienne-Lorette',
                'adresse' => '7050 boul. Hamel Ouest, suite 80',
                'ville' => 'Ancienne-Lorette',
                'code_postal' => 'G2G 1B5',
                'telephone' => '(418) 871-7545',
                'email' => 'anciennelorette@studiosunis.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Beauce',
                'adresse' => '17118 boul. Lacroix, Suite 2',
                'ville' => 'St-Georges-de-Beauce',
                'code_postal' => 'G5Y 8G9',
                'telephone' => '(418) 228-1441',
                'email' => 'beauce@studiosunis.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ... ajoute les 20 autres écoles ici avec la même structure ...
        ]);
    }
}
