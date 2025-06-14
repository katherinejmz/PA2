<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable = [
        'utilisateur_id',
        'commande_id',
        'montant',
        'sens',
        'type',
        'reference',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }
}
