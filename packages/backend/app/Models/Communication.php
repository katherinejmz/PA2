<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communication extends Model
{
    use HasFactory;

    protected $table = 'communications';

    protected $fillable = [
        'id_etape',
        'id_livreur',
        'message',
    ];

    // L'étape concernée par ce message
    public function etape()
    {
        return $this->belongsTo(EtapeLivraison::class, 'id_etape');
    }

    // Le livreur qui a envoyé ce message
    public function livreur()
    {
        return $this->belongsTo(Livreur::class, 'id_livreur');
    }
}
