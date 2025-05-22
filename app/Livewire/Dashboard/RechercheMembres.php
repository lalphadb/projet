<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Membre;

class RechercheMembres extends Component
{
    public string $query = '';

    public function render()
    {
        $membres = [];

        if (strlen($this->query) > 1) {
            $membres = Membre::where('prenom', 'like', '%' . $this->query . '%')
                             ->orWhere('nom', 'like', '%' . $this->query . '%')
                             ->orderBy('nom')
                             ->limit(10)
                             ->get();
        }

        return view('livewire.dashboard.recherche-membres', [
            'membres' => $membres,
        ]);
    }
}
