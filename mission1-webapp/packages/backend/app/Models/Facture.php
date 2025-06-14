<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    protected $fillable = [
        'utilisateur_id',
        'montant_total',
        'chemin_pdf',
        'date_emission',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
