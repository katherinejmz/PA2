<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    protected $fillable = [
        'entrepot_id',
        'code_box',
        'est_occupe',
    ];

    public function entrepot()
    {
        return $this->belongsTo(Entrepot::class, 'entrepot_id');
    }

    public function colis()
    {
        return $this->hasOne(Colis::class, 'box_id');
    }
}
