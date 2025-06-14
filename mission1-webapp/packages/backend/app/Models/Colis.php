<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colis extends Model
{
    protected $fillable = [
        'commande_id',
        'box_id',
        'livreur_id',
        'etat',
        'date_depot',
        'date_retrait',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }

    public function box()
    {
        return $this->belongsTo(Box::class, 'box_id');
    }

    public function livreur()
    {
        return $this->belongsTo(Utilisateur::class, 'livreur_id');
    }

    public function etapes()
    {
        return $this->hasMany(EtapeLivraison::class, 'colis_id');
    }
}
