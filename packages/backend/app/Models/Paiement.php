<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    protected $fillable = [
        'id_livraison',
        'montant',
        'date',
        'statut',
    ];

    // Relation vers la livraison associée
    public function livraison()
    {
        return $this->belongsTo(Livraison::class, 'id_livraison');
    }

    // Relation vers la facture générée
    public function facture()
    {
        return $this->hasOne(Facture::class, 'id_paiement');
    }
}
