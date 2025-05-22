<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'cours_id', 
        'date_presence',
        'status',
        'commentaire'
    ];

    protected $casts = [
        'date_presence' => 'date'
    ];

    // Constants pour les statuts
    const STATUS_PRESENT = 'present';
    const STATUS_ABSENT = 'absent';
    const STATUS_RETARD = 'retard';

    /**
     * 🎯 Obtenir tous les statuts disponibles avec leurs labels
     */
    public static function getStatuts()
    {
        return [
            self::STATUS_PRESENT => '✅ Présent',
            self::STATUS_ABSENT => '❌ Absent', 
            self::STATUS_RETARD => '⏰ Retard'
        ];
    }

    /**
     * 🔗 Relation avec le membre
     */
    public function membre(): BelongsTo
    {
        return $this->belongsTo(Membre::class);
    }

    /**
     * 🔗 Relation avec le cours
     */
    public function cours(): BelongsTo  
    {
        return $this->belongsTo(Cours::class);
    }

    /**
     * 📅 Scope pour filtrer les présences d'aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date_presence', Carbon::today());
    }

    /**
     * 📚 Scope pour filtrer par cours
     */
    public function scopeForCours($query, $coursId)
    {
        return $query->where('cours_id', $coursId);
    }

    /**
     * ✅ Scope pour les présents uniquement
     */
    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    /**
     * ❌ Scope pour les absents uniquement
     */
    public function scopeAbsent($query)
    {
        return $query->where('status', self::STATUS_ABSENT);
    }

    /**
     * ⏰ Scope pour les retards uniquement
     */
    public function scopeRetard($query)
    {
        return $query->where('status', self::STATUS_RETARD);
    }

    /**
     * 📊 Scope pour une période donnée
     */
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_presence', [$dateDebut, $dateFin]);
    }

    /**
     * 🏫 Scope pour filtrer par école (via le cours)
     */
    public function scopeParEcole($query, $ecoleId)
    {
        return $query->whereHas('cours', function($q) use ($ecoleId) {
            $q->where('ecole_id', $ecoleId);
        });
    }

    /**
     * 🔍 Vérifier si une présence existe déjà
     */
    public static function existePresence($membreId, $coursId, $date)
    {
        return self::where([
            'membre_id' => $membreId,
            'cours_id' => $coursId,
            'date_presence' => $date
        ])->exists();
    }

    /**
     * 📈 Obtenir les statistiques de présence pour un cours et une date
     */
    public static function getStatistiquesCours($coursId, $date = null)
    {
        $date = $date ?? Carbon::today();
        
        return self::where('cours_id', $coursId)
            ->whereDate('date_presence', $date)
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as presents,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absents,
                SUM(CASE WHEN status = "retard" THEN 1 ELSE 0 END) as retards
            ')
            ->first();
    }

    /**
     * 📊 Calculer le taux de présence
     */
    public static function calculerTauxPresence($coursId, $date = null)
    {
        $stats = self::getStatistiquesCours($coursId, $date);
        
        if (!$stats || $stats->total == 0) {
            return 0;
        }
        
        return round((($stats->presents + $stats->retards) / $stats->total) * 100, 1);
    }

    /**
     * ✅ Vérifier si le membre est présent
     */
    public function estPresent(): bool
    {
        return $this->status === self::STATUS_PRESENT;
    }

    /**
     * ❌ Vérifier si le membre est absent
     */
    public function estAbsent(): bool
    {
        return $this->status === self::STATUS_ABSENT;
    }

    /**
     * ⏰ Vérifier si le membre est en retard
     */
    public function estEnRetard(): bool
    {
        return $this->status === self::STATUS_RETARD;
    }

    /**
     * 🏷️ Accesseur pour obtenir le label du statut
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuts()[$this->status] ?? $this->status;
    }

    /**
     * 🎨 Accesseur pour obtenir la classe CSS du statut
     */
    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PRESENT => 'success',
            self::STATUS_ABSENT => 'danger',
            self::STATUS_RETARD => 'warning',
            default => 'secondary'
        };
    }

    /**
     * 🔢 Accesseur pour obtenir l'icône du statut
     */
    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PRESENT => 'fas fa-check-circle',
            self::STATUS_ABSENT => 'fas fa-times-circle',
            self::STATUS_RETARD => 'fas fa-clock',
            default => 'fas fa-question-circle'
        };
    }

    /**
     * 📅 Obtenir les présences d'un membre pour une période
     */
    public static function getPresencesMembre($membreId, $dateDebut = null, $dateFin = null)
    {
        $query = self::where('membre_id', $membreId)
            ->with(['cours']);
            
        if ($dateDebut) {
            $query->where('date_presence', '>=', $dateDebut);
        }
        
        if ($dateFin) {
            $query->where('date_presence', '<=', $dateFin);
        }
        
        return $query->orderBy('date_presence', 'desc')->get();
    }

    /**
     * 📊 Statistiques globales d'un membre
     */
    public static function getStatistiquesMembre($membreId, $dateDebut = null, $dateFin = null)
    {
        $query = self::where('membre_id', $membreId);
        
        if ($dateDebut) {
            $query->where('date_presence', '>=', $dateDebut);
        }
        
        if ($dateFin) {
            $query->where('date_presence', '<=', $dateFin);
        }
        
        return $query->selectRaw('
                COUNT(*) as total_cours,
                SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as presents,
                SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as absents,
                SUM(CASE WHEN status = "retard" THEN 1 ELSE 0 END) as retards,
                ROUND(
                    (SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1
                ) as taux_presence
            ')
            ->first();
    }

    /**
     * 🔄 Mutateur pour s'assurer que le statut est valide
     */
    public function setStatusAttribute($value)
    {
        $validStatuses = [self::STATUS_PRESENT, self::STATUS_ABSENT, self::STATUS_RETARD];
        
        if (!in_array($value, $validStatuses)) {
            $value = self::STATUS_PRESENT;
        }
        
        $this->attributes['status'] = $value;
    }

    /**
     * 📝 Scope pour rechercher par commentaire
     */
    public function scopeAvecCommentaire($query, $recherche = null)
    {
        if ($recherche) {
            return $query->where('commentaire', 'like', "%{$recherche}%");
        }
        
        return $query->whereNotNull('commentaire')
                    ->where('commentaire', '!=', '');
    }

    /**
     * 🗓️ Obtenir le nombre de jours de cours d'un membre ce mois
     */
    public static function getJoursCoursMembreCeMois($membreId)
    {
        return self::where('membre_id', $membreId)
            ->whereMonth('date_presence', Carbon::now()->month)
            ->whereYear('date_presence', Carbon::now()->year)
            ->distinct('date_presence')
            ->count('date_presence');
    }
}
