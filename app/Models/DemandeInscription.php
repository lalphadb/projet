<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeInscription extends Model
{
    use HasFactory;

    protected $table = 'demandes_inscriptions';

    protected $fillable = [
        'membre_id',
        'cours_id',
        'statut',
        'date_demande',
    ];
}
