<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LienPersonnalise extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'destination',
    ];
}
