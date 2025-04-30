<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LivreurEtape extends Model
{
    use HasFactory;

    protected $table = 'livreur_etape';

    protected $fillable = [
        'id_etape',
        'id_livreur',
        'statut',
    ];

    // Relation vers l'étape de livraison
    public function etape()
    {
        return $this->belongsTo(EtapeLivraison::class, 'id_etape');
    }

    // Relation vers le livreur affecté
    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'id_livreur');
    }
}
