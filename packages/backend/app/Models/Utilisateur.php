<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relations avec les rôles
    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'id_utilisateur');
    }

    public function commercant()
    {
        return $this->hasOne(Commercant::class, 'id', 'id_utilisateur');
    }

    public function prestataire()
    {
        return $this->hasOne(Prestataire::class, 'id', 'id_utilisateur');
    }

    public function livreur()
    {
        return $this->hasOne(Livreur::class, 'id', 'id_utilisateur');
    }
}
