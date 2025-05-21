<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceinture;

class CeintureSeeder extends Seeder
{
    public function run(): void
    {
        $noms = [
            'Blanche',
            'Jaune',
            'Orange',
            'Violet',
            'Bleue',
            'Bleue I',
            'Verte',
            'Verte I',
            'Brune I',
            'Brune II',
            'Brune III',
            'Noire (Shodan)',
            'Noire (Nidan)',
            'Noire (Sandan)',
            'Noire (Yondan)',
            'Noire (Godan)',
            'Noire (Rokudan)',
            'Noire (Nanadan)',
        ];

        Ceinture::query()->delete();
        foreach ($noms as $index => $nom) {
            Ceinture::create([
                'nom' => $nom,
                'ordre' => $index + 1,
                'couleur' => null,
            ]);
        }
    }
}
