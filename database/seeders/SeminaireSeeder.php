<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seminaire;
use Carbon\Carbon;

class SeminaireSeeder extends Seeder
{
    public function run(): void
    {
         Seminaire::query()->delete();
        $seminaires = [
            [
                'titre' => 'Stage d\'été enfants',
                'date' => Carbon::now()->addDays(10)->toDateString(),
                'lieu' => 'Dojo Québec',
                'description' => 'Stage intensif pour les jeunes karatékas de niveau débutant à intermédiaire.',
            ],
            [
                'titre' => 'Masterclass ceintures noires',
                'date' => Carbon::now()->addWeeks(3)->toDateString(),
                'lieu' => 'Centre sportif St-Émile',
                'description' => 'Entraînement technique avancé pour ceintures noires (1er dan et +).',
            ],
            [
                'titre' => 'Séminaire de perfectionnement adultes',
                'date' => Carbon::now()->addMonth()->toDateString(),
                'lieu' => 'Dojo Lévis',
                'description' => 'Pour les adultes de niveau ceinture verte et plus.',
            ],
        ];

        foreach ($seminaires as $data) {
            Seminaire::create($data);
        }
    }
}
