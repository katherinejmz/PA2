<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'code_postal',
    ];

    public function box()
    {
        return $this->hasMany(Box::class, 'entrepot_id');
    }
}
