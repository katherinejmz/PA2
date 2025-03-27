<?php

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = ['id_livraison', 'montant', 'date', 'statut'];

    public function livraison()
    {
        return $this->belongsTo(Livraison::class, 'id_livraison');
    }

    public function facture()
    {
        return $this->hasOne(Facture::class, 'id_paiement');
    }
}

