<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'date_naissance',
        'sexe',
        'adresse',
        'numero_rue',
        'nom_rue',
        'ville',
        'province',
        'code_postal',
        'ecole_id',
        'approuve',
        'mot_de_passe_hash',
        'date_enregistrement',
    ];

    /**
     * 🔗 Relation avec l’école (1 membre appartient à 1 école)
     */
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }

    /**
     * 🔗 Relation avec les ceintures obtenues (many-to-many avec date d'obtention)
     */
    public function ceintures()
    {
        return $this->belongsToMany(Ceinture::class, 'ceintures_membres')
                    ->withPivot('date_obtention')
                    ->withTimestamps();
    }

    /**
     * 🔗 Relation avec les séminaires suivis (many-to-many)
     */
    public function seminaires()
    {
        return $this->belongsToMany(Seminaire::class, 'membre_seminaire')
                    ->withTimestamps();
    }

    /**
     * 🔗 Relation avec les cours suivis (many-to-many)
     */
    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'inscriptions_cours');
    }
}

