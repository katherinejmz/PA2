<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    use HasFactory;

    protected $table = 'livreurs';

    protected $fillable = [
        'id_utilisateur',
        'vehicule',
        'note_moyenne',
        'nombre_livraisons',
    ];

    // Lien vers l'utilisateur principal
    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'id_utilisateur');
    }

    // Étapes auxquelles ce livreur est affecté
    public function etapes()
    {
        return $this->belongsToMany(EtapeLivraison::class, 'livreur_etape', 'id_livreur', 'id_etape')
                    ->withTimestamps()
                    ->withPivot('statut');
    }

    // Messages envoyés par ce livreur
    public function communications()
    {
        return $this->hasMany(Communication::class, 'id_livreur');
    }

    // Évaluations reçues
    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'id_livreur');
    }
}
