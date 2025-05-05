<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EtapeLivraison extends Model
{
    protected $table = 'etapes_livraison';

    protected $fillable = [
        'colis_id',
        'statut',
        'commentaire',
        'date_etape',
    ];

    public function colis()
    {
        return $this->belongsTo(Colis::class, 'colis_id');
    }
}
