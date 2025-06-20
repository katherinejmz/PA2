<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrajetLivreur extends Model
{
    use HasFactory;

    protected $table = 'trajets_livreurs';
    
    protected $fillable = [
        'livreur_id',
        'ville_depart',
        'ville_arrivee',
        'disponible_du',
        'disponible_au',
    ];

    public function livreur()
    {
        return $this->belongsTo(Utilisateur::class, 'livreur_id');
    }
}
