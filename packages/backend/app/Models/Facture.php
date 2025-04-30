<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $table = 'factures';

    protected $fillable = [
        'id_paiement',
        'pdf_facture',
        'date_emission',
    ];

    // Paiement associé à la facture
    public function paiement()
    {
        return $this->belongsTo(Paiement::class, 'id_paiement');
    }
}

