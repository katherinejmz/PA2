<?php

class Facture extends Model
{
    use HasFactory;

    protected $fillable = ['id_paiement', 'pdf_facture', 'date_emission'];

    public function paiement()
    {
        return $this->belongsTo(Paiement::class, 'id_paiement');
    }
}

