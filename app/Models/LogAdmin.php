<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAdmin extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action',
        'date_action',
        'ip_address',
        'user_agent',
        'details',
    ];
}
