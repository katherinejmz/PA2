<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    protected $fillable = [
        'expediteur_id',
        'destinataire_id',
        'annonce_id',
        'message',
        'lu_at',
    ];

    public function expediteur()
    {
        return $this->belongsTo(Utilisateur::class, 'expediteur_id');
    }

    public function destinataire()
    {
        return $this->belongsTo(Utilisateur::class, 'destinataire_id');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class, 'annonce_id');
    }
}
