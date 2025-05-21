<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscriptionCours extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'membre_id', 'cours_id', 'session_id', 'statut',
        'raison_changement'
    ];
    
    // Relations
    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }
    
    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
    
    public function session()
    {
        return $this->belongsTo(CoursSession::class, 'session_id');
    }
}
