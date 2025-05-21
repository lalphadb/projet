<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'ecole_id',
        'nom_utilisateur',
        'mot_de_passe_hash',
        'email',
        'role',
        'protected',
        'reset_token',
        'reset_token_expiry',
    ];

    // Relation facultative vers l’école
    public function ecole()
    {
        return $this->belongsTo(Ecole::class);
    }
}

