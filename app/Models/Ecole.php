<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ecole extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'province',
        'telephone',
        'responsable',
        'active'
    ];
    
    protected $casts = [
        'active' => 'boolean',
    ];
    
    public function cours()
    {
        return $this->hasMany(Cours::class);
    }
    
    public function sessions()
    {
        return $this->hasMany(CoursSession::class);
    }
    
    public function membres()
    {
        return $this->hasMany(Membre::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
