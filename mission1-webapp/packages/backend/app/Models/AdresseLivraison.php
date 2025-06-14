<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdresseLivraison extends Model
{
    protected $table = 'adresses_livraison';
    protected $fillable = [
        'commande_id',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'instructions'
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
}
