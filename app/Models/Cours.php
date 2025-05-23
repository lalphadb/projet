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
        'instructeur',
        'niveau',
        'tarif',
        'statut',
        'lieu',
        'capacite',
        'prix',
        'visible',
        'places_max'
    ];
    
    protected $casts = [
        'visible' => 'boolean',
        'capacite' => 'integer',
        'prix' => 'float',
        'tarif' => 'float',
        'places_max' => 'integer',
        'jours' => 'array', // Gardé pour compatibilité mais on utilisera horaires()
    ];
    
    // Relations existantes
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
    
    // NOUVELLES RELATIONS pour les horaires
    public function horaires()
    {
        return $this->hasMany(CoursHoraire::class)->orderBy('jour')->orderBy('heure_debut');
    }
    
    public function horairesActifs()
    {
        return $this->hasMany(CoursHoraire::class)->where('active', true)->orderBy('jour')->orderBy('heure_debut');
    }
    
    // Scopes existants
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
    
    // NOUVEAUX SCOPES pour les horaires
    public function scopeParJour($query, $jour)
    {
        return $query->whereHas('horaires', function($q) use ($jour) {
            $q->where('jour', strtolower($jour))->where('active', true);
        });
    }
    
    public function scopeParCreneauHoraire($query, $heureDebut, $heureFin)
    {
        return $query->whereHas('horaires', function($q) use ($heureDebut, $heureFin) {
            $q->where('active', true)
              ->where(function($sq) use ($heureDebut, $heureFin) {
                  $sq->whereBetween('heure_debut', [$heureDebut, $heureFin])
                     ->orWhereBetween('heure_fin', [$heureDebut, $heureFin])
                     ->orWhere(function($ssq) use ($heureDebut, $heureFin) {
                         $ssq->where('heure_debut', '<=', $heureDebut)
                            ->where('heure_fin', '>=', $heureFin);
                     });
              });
        });
    }
    
    // Méthodes existantes
    public function estComplet()
    {
        if (isset($this->statut) && $this->statut === 'complet') {
            return true;
        }
        return $this->places_max > 0 && $this->inscriptions()->count() >= $this->places_max;
    }
    
    public function placesDisponibles()
    {
        if (isset($this->statut) && $this->statut !== 'actif') {
            return 0;
        }
        return max(0, $this->places_max - $this->inscriptions()->count());
    }
    
    // NOUVELLES MÉTHODES utilitaires pour les horaires
    public function getJoursListe()
    {
        return $this->horairesActifs->pluck('jour')->unique()->sort()->values();
    }
    
    public function getHorairesParJour()
    {
        return $this->horairesActifs->groupBy('jour');
    }
    
    public function getHorairesFormates()
    {
        return $this->horairesActifs->map(function($horaire) {
            return $horaire->getHoraireComplet();
        })->implode(', ');
    }
    
    public function getDureeHebdomadaire()
    {
        return $this->horairesActifs->sum(function($horaire) {
            return $horaire->getDureeEnMinutes();
        });
    }
    
    public function aSimilaireHoraires(Cours $autreCours)
    {
        $mesHoraires = $this->horairesActifs->map(function($h) {
            return $h->jour . '_' . $h->heure_debut . '_' . $h->heure_fin;
        })->sort();
        
        $autresHoraires = $autreCours->horairesActifs->map(function($h) {
            return $h->jour . '_' . $h->heure_debut . '_' . $h->heure_fin;
        })->sort();
        
        return $mesHoraires->toArray() === $autresHoraires->toArray();
    }
    
    // Méthode pour la compatibilité avec l'ancien système
    public function getJoursFormateLegacy()
    {
        // Si on a des horaires dans la nouvelle table, les utiliser
        if ($this->horairesActifs->count() > 0) {
            return $this->getHorairesFormates();
        }
        
        // Sinon, utiliser l'ancien format
        if (is_array($this->jours)) {
            return implode(', ', array_map('ucfirst', $this->jours));
        }
        
        return $this->jours ?? '';
    }
}
