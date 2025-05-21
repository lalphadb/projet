<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'membre_id',
        'cours_id',
        'date_presence',
        'heure_arrivee',
        'heure_depart',
        'est_present',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class);
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
}
