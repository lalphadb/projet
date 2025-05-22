<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seminaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'date',
        'lieu',
        'description',
    ];

    public function membres()
    {
        return $this->belongsToMany(Membre::class, 'membre_seminaire');
    }
}
