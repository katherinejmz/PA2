<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commercant extends Model
{
    use HasFactory;

    protected $table = 'commercants';

    protected $fillable = [
        'id_utilisateur',
        'entreprise',
        'adresse',
        'siret',
    ];

    // L'utilisateur associé à ce commerçant
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Les annonces publiées par ce commerçant
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'id_commercant');
    }
}

