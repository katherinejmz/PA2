<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'prestation_id',
        'commentaire_client',
        'note',
        'statut_final',
    ];

    public function prestation()
    {
        return $this->belongsTo(Prestation::class);
    }
}
