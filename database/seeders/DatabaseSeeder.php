<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
$this->call([
    EcoleSeeder::class,
    SeminaireSeeder::class,
    MembreSeeder::class,
    SeminaireInscriptionSeeder::class,
    CeintureSeeder::class, // ✅ juste le nom de la classe
]);
 }
}
