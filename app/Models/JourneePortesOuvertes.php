<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JourneePortesOuvertes extends Model
{
    use HasFactory;

    protected $table = 'journees_portes_ouvertes';

    protected $fillable = [
        'ecole_id',
        'date_evenement',
        'description',
    ];
}
