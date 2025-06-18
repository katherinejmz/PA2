<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanningPrestataire extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestataire_id',
        'date_disponible',
        'heure_debut',
        'heure_fin',
    ];

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }
}
