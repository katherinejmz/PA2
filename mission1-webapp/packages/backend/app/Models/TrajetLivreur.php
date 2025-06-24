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
        'entrepot_depart_id',
        'entrepot_arrivee_id',
        'disponible_du',
        'disponible_au',
    ];

    public function livreur()
    {
        return $this->belongsTo(Utilisateur::class, 'livreur_id');
    }

    public function entrepotDepart()
    {
        return $this->belongsTo(Entrepot::class, 'entrepot_depart_id');
    }

    public function entrepotArrivee()
    {
        return $this->belongsTo(Entrepot::class, 'entrepot_arrivee_id');
    }
}
