<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapeLivraison extends Model
{
    use HasFactory;

    protected $table = 'etapes_livraison';

    protected $fillable = [
        'id_livraison',
        'ordre',
        'lieu_depart',
        'lieu_arrivee',
        'statut',
        'date_prise_en_charge',
    ];

    // La livraison principale à laquelle cette étape appartient
    public function livraison()
    {
        return $this->belongsTo(Livraison::class, 'id_livraison');
    }

    // Livreurs affectés à cette étape
    public function livreurs()
    {
        return $this->belongsToMany(Livreur::class, 'livreur_etape', 'id_etape', 'id_livreur')
                    ->withTimestamps()
                    ->withPivot('statut');
    }

    // Communications échangées durant cette étape
    public function communications()
    {
        return $this->hasMany(Communication::class, 'id_etape');
    }
}
