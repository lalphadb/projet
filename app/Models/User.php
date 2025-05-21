<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'prenom',      // Ajouté car utilisé dans le contrôleur
        'nom',         // Ajouté car utilisé dans le contrôleur
        'name',
        'email',
        'password',
        'role',        // Ajouté car utilisé par le middleware checkrole
        'ecole_id',    // Ajouté car utilisé dans le contrôleur
        'membre_id',   // Ajouté car utilisé dans le contrôleur
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relation avec l'école (many-to-one)
     * Un utilisateur appartient à une école
     */
    public function ecole()
    {
        return $this->belongsTo(\App\Models\Ecole::class, 'ecole_id');
    }

    /**
     * Relation avec le membre (one-to-one)
     * Un utilisateur peut être lié à un membre
     */
    public function membre()
    {
        return $this->belongsTo(\App\Models\Membre::class, 'membre_id');
    }

    /**
     * Vérifie si l'utilisateur est un super administrateur.
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    /**
     * Vérifie si l'utilisateur est un administrateur.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un instructeur.
     *
     * @return bool
     */
    public function isInstructeur()
    {
        return $this->role === 'instructeur';
    }
}
