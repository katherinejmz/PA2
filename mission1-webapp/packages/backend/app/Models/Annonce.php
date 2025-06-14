<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;


    protected $fillable = [
        'type',
        'titre',
        'description',
        'prix_propose',
        'photo',
        'id_client',
        'id_commercant',
        'id_prestataire',
        'lieu_depart',
        'lieu_arrivee',
    ];

    // Client associé à l'annonce
    public function client()
    {
        return $this->belongsTo(Utilisateur::class, 'id_client');
    }

    // Commerçant (seulement si type = produit_livre)
    public function commercant()
    {
        return $this->belongsTo(Utilisateur::class, 'id_commercant');
    }

    // Prestataire (seulement si type = service)
    public function prestataire()
    {
        return $this->belongsTo(Utilisateur::class, 'id_prestataire');
    }

    // Livreurs associés via la table pivot (si type = livraison_client ou produit_livre)
    public function livreurs()
    {
        return $this->belongsToMany(Utilisateur::class, 'annonce_utilisateur', 'annonce_id', 'utilisateur_id');
    }


    // Commandes (achat/réservation d'une annonce)
    public function commandes()
    {
        return $this->hasMany(Commande::class, 'annonce_id');
    }

    public function colis()
    {
        return $this->hasOne(Colis::class);
    }
    
}
