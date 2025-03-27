<?php

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_client', 'id_commercant', 'description',
        'lieu_depart', 'lieu_arrivee', 'prix_proposé'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function commercant()
    {
        return $this->belongsTo(Commercant::class, 'id_commercant');
    }

    public function livraison()
    {
        return $this->hasOne(Livraison::class, 'id_annonce');
    }
}

