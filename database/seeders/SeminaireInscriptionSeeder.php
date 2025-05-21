<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Membre;
use App\Models\Seminaire;

class SeminaireInscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $membres = Membre::all();
        $seminaires = Seminaire::all();

        if ($membres->isEmpty() || $seminaires->isEmpty()) {
            $this->command->warn('Aucun membre ou séminaire disponible.');
            return;
        }

        foreach ($membres as $membre) {
            // Nombre aléatoire de séminaires pour ce membre (0 à 3)
            $seminairesToAttach = $seminaires->random(rand(0, min(3, $seminaires->count())));

            foreach ($seminairesToAttach as $seminaire) {
                $membre->seminaires()->syncWithoutDetaching([$seminaire->id]);
            }
        }

        $this->command->info('Inscription des membres à des séminaires : OK');
    }
}
