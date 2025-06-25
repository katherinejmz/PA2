<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapeLivraison extends Model
{
    use HasFactory;

    protected $table = 'etapes_livraison';

    protected $fillable = [
        'annonce_id',
        'livreur_id',
        'lieu_depart',
        'lieu_arrivee',
        'statut',
        'est_client',
        'est_commercant',
    ];

    protected $casts = [
        'est_client' => 'boolean',
        'est_commercant' => 'boolean',
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }

    public function livreur()
    {
        return $this->belongsTo(Utilisateur::class, 'livreur_id');
    }

    public function codes()
    {
        return $this->hasMany(CodeBox::class, 'etape_livraison_id');
    }
}
