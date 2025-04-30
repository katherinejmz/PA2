<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';

    protected $fillable = [
        'id_client',
        'id_livreur',
        'id_prestataire',
        'note',
        'commentaire',
    ];

    // Émetteur de l'évaluation
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    // Cible : livreur évalué
    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'id_livreur');
    }

    // Cible : prestataire évalué
    public function prestataire()
    {
        return $this->belongsTo(Prestataire::class, 'id_prestataire');
    }
}
