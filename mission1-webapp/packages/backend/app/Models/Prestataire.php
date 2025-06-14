<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestataire extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'domaine',
        'description',
        'valide',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}