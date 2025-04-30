<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $table = 'annonces';

    protected $fillable = [
        'id_client',
        'id_commercant',
        'description',
        'lieu_depart',
        'lieu_arrivee',
        'prix_propose',
    ];

    // Relation vers le client ayant publié l'annonce
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    // Relation vers le commerçant ayant publié l'annonce
    public function commercant()
    {
        return $this->belongsTo(Commercant::class, 'id_commercant');
    }

    // Relation avec la livraison associée à cette annonce
    public function livraison()
    {
        return $this->hasOne(Livraison::class, 'id_annonce');
    }
}

