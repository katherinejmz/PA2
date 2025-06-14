<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'utilisateur_id',
        'adresse',
        'telephone',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }
}
