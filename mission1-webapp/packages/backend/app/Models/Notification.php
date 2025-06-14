<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'utilisateur_id',
        'titre',
        'contenu',
        'cible_type',
        'cible_id',
        'lu_at',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function cible()
    {
        return $this->morphTo();
    }
}
