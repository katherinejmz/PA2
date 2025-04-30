<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestataire extends Model
{
    use HasFactory;

    protected $table = 'prestataires';

    protected $fillable = [
        'id_utilisateur',
        'type_service',
        'tarif_horaire',
    ];

    // Relation avec l'utilisateur principal
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Évaluations données au prestataire
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_prestataire');
    }
}

