<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PorteOuverte extends Model
{
    use HasFactory;

    protected $table = 'journees_portes_ouvertes';

    protected $fillable = [
        'titre',
        'date_debut',
        'date_fin',
        'lieu',
    ];
}
