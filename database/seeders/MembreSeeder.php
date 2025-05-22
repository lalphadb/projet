<?php

namespace Database\Seeders;

use App\Models\Membre;
use App\Models\Ecole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MembreSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('membres')->delete();

        $ecoleIds = Ecole::pluck('id')->toArray();

        if (empty($ecoleIds)) {
            $this->command->warn('Aucune école disponible pour lier les membres.');
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Membre::create([
                'prenom' => 'TestPrenom' . $i,
                'nom' => 'TestNom' . $i,
                'email' => 'membre' . $i . '@test.com',
                'telephone' => '418-555-12' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'date_naissance' => now()->subYears(rand(10, 35))->format('Y-m-d'),
                'sexe' => rand(0, 1) ? 'H' : 'F',
                'numero_rue' => rand(100, 999),
                'nom_rue' => 'Rue Principale',
                'ville' => 'Québec',
                'province' => 'QC',
                'code_postal' => 'G1K 1A' . rand(1, 9),
                'ecole_id' => $ecoleIds[array_rand($ecoleIds)],
                'approuve' => true,
               // 'mot_de_passe_hash' => bcrypt(Str::random(10)),
            ]);
        }
    }
}

