<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    use HasFactory;

    protected $table = 'livraisons';

    protected $fillable = [
        'id_annonce',
        'date_prise_en_charge',
        'statut',
    ];

    // Lien vers l'annonce d'origine
    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce');
    }

    // Liste des étapes de cette livraison
    public function etapes()
    {
        return $this->hasMany(EtapeLivraison::class, 'id_livraison');
    }

    // Paiement lié à cette livraison
    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'id_livraison');
    }
}

