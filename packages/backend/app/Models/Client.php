<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'id_utilisateur',
        'adresse',
        'telephone',
        'date_inscription',
    ];

    // Relation vers l'utilisateur général
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Les annonces publiées par ce client
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'id_client');
    }

    // Les évaluations émises par ce client
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_client');
    }
}

