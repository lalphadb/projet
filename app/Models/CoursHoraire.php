<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CoursHoraire extends Model
{
    use HasFactory;
    
    protected $table = 'cours_horaires';
    
    protected $fillable = [
        'cours_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'salle',
        'notes',
        'active'
    ];
    
    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'active' => 'boolean',
    ];
    
    // Relations
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
    
    // Scopes
    public function scopeActif($query)
    {
        return $query->where('active', true);
    }
    
    public function scopeParJour($query, $jour)
    {
        return $query->where('jour', strtolower($jour));
    }
    
    // Méthodes utilitaires
    public function getDureeEnMinutes()
    {
        $debut = Carbon::parse($this->heure_debut);
        $fin = Carbon::parse($this->heure_fin);
        return $debut->diffInMinutes($fin);
    }
    
    public function getJourFormate()
    {
        $jours = [
            'lundi' => 'Lundi',
            'mardi' => 'Mardi',
            'mercredi' => 'Mercredi',
            'jeudi' => 'Jeudi',
            'vendredi' => 'Vendredi',
            'samedi' => 'Samedi',
            'dimanche' => 'Dimanche'
        ];
        
        return $jours[$this->jour] ?? ucfirst($this->jour);
    }
    
    public function getHoraireFormate()
    {
        return Carbon::parse($this->heure_debut)->format('H:i') . ' - ' . Carbon::parse($this->heure_fin)->format('H:i');
    }
    
    public function getHoraireComplet()
    {
        return $this->getJourFormate() . ' ' . $this->getHoraireFormate();
    }
}
