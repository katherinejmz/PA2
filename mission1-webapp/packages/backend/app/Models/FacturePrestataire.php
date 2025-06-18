<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacturePrestataire extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestataire_id',
        'mois',
        'montant_total',
        'chemin_pdf',
    ];

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }
}
