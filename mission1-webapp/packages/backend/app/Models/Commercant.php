<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commercant extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'nom_entreprise',
        'siret',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
