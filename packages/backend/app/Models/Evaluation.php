<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = ['utilisateur_id', 'client_id', 'annonce_id', 'note', 'commentaire'];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function client()
    {
        return $this->belongsTo(Utilisateur::class, 'client_id');
    }

    public function annonce()
    {
        return $this->belongsTo(Annonce::class);
    }
}
