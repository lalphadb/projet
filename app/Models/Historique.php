<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'membre_id',
        'action',
        'details',
        'date_action',
        'date',
    ];
}
