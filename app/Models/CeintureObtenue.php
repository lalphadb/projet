<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CeintureObtenue extends Model
{
    use HasFactory;

    protected $table = 'ceintures_obtenues';

    protected $fillable = [
        'membre_id',
        'ceinture_id',
        'date_obtention',
    ];
}
