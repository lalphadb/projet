<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CeintureMembre extends Model
{
    use HasFactory;

    protected $table = 'ceintures_membres';

    protected $fillable = [
        'membre_id',
        'ceinture_id',
        'date_obtention',
        'commentaire',
    ];
}
