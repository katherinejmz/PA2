<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CodeBox extends Model
{
    protected $table = 'codes_box';

    protected $fillable = [
        'box_id',
        'etape_livraison_id',
        'type',
        'code_temporaire',
        'utilise',
    ];

    public function etapeLivraison()
    {
        return $this->belongsTo(EtapeLivraison::class);
    }

    public function box()
    {
        return $this->belongsTo(Box::class);
    }
}
