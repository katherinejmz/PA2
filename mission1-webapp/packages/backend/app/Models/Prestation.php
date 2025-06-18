<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestation extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestataire_id',
        'client_id',
        'type_prestation',
        'description',
        'date_heure',
        'duree_estimee',
        'tarif',
        'statut',
    ];

    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function intervention()
    {
        return $this->hasOne(Intervention::class);
    }
}
