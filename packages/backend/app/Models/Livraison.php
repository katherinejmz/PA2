<?php

class Livraison extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_annonce', 'id_livreur', 'id_prestataire',
        'date_prise_en_charge', 'statut'
    ];

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'id_annonce');
    }

    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'id_livreur');
    }

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire');
    }

    public function paiement()
    {
        return $this->hasOne(Paiement::class, 'id_livraison');
    }
}

