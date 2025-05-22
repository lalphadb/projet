<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Cours; 

class CoursSession extends Model
{
    use HasFactory;
    
    protected $table = 'cours_sessions';
    
    protected $fillable = [
        'nom', 'mois', 'date_debut', 'date_fin', 'ecole_id',
        'description', 'inscriptions_actives', 'visible',
        'date_limite_inscription', 'couleur'
    ];
    
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_limite_inscription' => 'date',
        'inscriptions_actives' => 'boolean',
        'visible' => 'boolean',
    ];
    
    // Relations
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }
    
    public function cours()
    {
        return $this->hasMany(Cours::class, 'session_id');
    }
    
    // Scopes
    public function scopeActif($query)
    {
        $now = Carbon::now();
        return $query->where('date_debut', '<=', $now)
                     ->where('date_fin', '>=', $now);
    }
    
    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>', Carbon::now());
    }
    
    public function scopeTermine($query)
    {
        return $query->where('date_fin', '<', Carbon::now());
    }
}
