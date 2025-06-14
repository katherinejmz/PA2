<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'annonce_id',
        'client_id',
        'montant',
        'statut',
        'achete_le',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'annonce_id');
    }

    public function client()
    {
        return $this->belongsTo(Utilisateur::class, 'client_id');
    }

    public function colis()
    {
        return $this->hasOne(Colis::class, 'commande_id');
    }

    public function adresseLivraison()
    {
        return $this->hasOne(AdresseLivraison::class);
    }


}
