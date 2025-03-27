<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Utilisateur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'mot_de_passe',
        'role'
    ];

    // Relations spécifiques selon le rôle
    public function client()
    {
        return $this->hasOne(Client::class, 'id');
    }

    public function commercant()
    {
        return $this->hasOne(Commercant::class, 'id');
    }

    public function prestataire()
    {
        return $this->hasOne(Prestataire::class, 'id');
    }

    public function livreur()
    {
        return $this->hasOne(Livreur::class, 'id');
    }

    // Evaluation reçues si c’est un prestataire ou livreur
    public function evaluationsRecues()
    {
        return $this->hasMany(Evaluation::class, 'id_prestataire')->orWhere('id_livreur', $this->id);
    }
}
