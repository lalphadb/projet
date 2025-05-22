<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;
    
    protected $table = 'cours';
    
    protected $fillable = [
        'nom',
        'description',
        'session_id',
        'ecole_id',
        'formateur_id',
        'jours',
        'instructeur',
        'niveau',
        'tarif',
        'statut',
        'lieu',
        'heure_debut',
        'heure_fin',
        'capacite',
        'prix',
        'visible'
    ];
    
    protected $casts = [
        'visible' => 'boolean',
        'capacite' => 'integer',
        'prix' => 'float',
        'tarif' => 'float',
        'jours' => 'array',
    ];
    
    // Relations
    public function session()
    {
        return $this->belongsTo(CoursSession::class, 'session_id');
    }
    
    public function ecole()
    {
        return $this->belongsTo(Ecole::class, 'ecole_id');
    }
    
    public function inscriptions()
    {
        return $this->hasMany(InscriptionCours::class, 'cours_id');
    }
    
    // Scopes
    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }
    
    public function scopeActif($query)
    {
        return $query->where('statut', 'actif');
    }
    
    public function scopeParSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }
    
    public function scopeParEcole($query, $ecoleId)
    {
        return $query->where('ecole_id', $ecoleId);
    }
    
    // Méthodes
    public function estComplet()
    {
        if (isset($this->statut) && $this->statut === 'complet') {
            return true;
        }
        return $this->capacite > 0 && $this->inscriptions()->count() >= $this->capacite;
    }
    
    public function placesDisponibles()
    {
        if (isset($this->statut) && $this->statut !== 'actif') {
            return 0;
        }
        return max(0, $this->capacite - $this->inscriptions()->count());
    }
}
