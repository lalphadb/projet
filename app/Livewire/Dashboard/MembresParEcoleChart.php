<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Ecole;
use App\Models\Membre;
use Illuminate\Support\Facades\Auth;

class MembresParEcoleChart extends Component
{
    public $labels = [];
    public $data = [];

    public function mount()
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            // Superadmin : toutes les écoles
            $ecoles = Ecole::withCount('membres')->get();
        } else {
            // Admin d’école : seulement ses écoles
            $ecoles = Ecole::withCount('membres')
                ->where('id', $user->ecole_id)
                ->get();
        }

        $this->labels = $ecoles->pluck('nom')->toArray();
        $this->data = $ecoles->pluck('membres_count')->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.membres-par-ecole-chart');
    }
}
