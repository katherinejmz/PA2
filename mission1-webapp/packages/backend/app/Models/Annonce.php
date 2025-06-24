<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Annonce extends Model
{
    use HasFactory;
    
    protected $appends = ['statut'];

    protected $fillable = [
        'type',
        'titre',
        'description',
        'prix_propose',
        'photo',
        'id_client',
        'id_commercant',
        'id_prestataire',
        'entrepot_depart_id',
        'entrepot_arrivee_id',
    ];

    public function client()
    {
        return $this->belongsTo(Utilisateur::class, 'id_client');
    }

    public function commercant()
    {
        return $this->belongsTo(Utilisateur::class, 'id_commercant');
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class, 'annonce_id');
    }

    public function colis()
    {
        return $this->hasOne(Colis::class);
    }

    public function etapesLivraison()
    {
        return $this->hasMany(EtapeLivraison::class, 'annonce_id');
    }

    public function entrepotDepart()
    {
        return $this->belongsTo(Entrepot::class, 'entrepot_depart_id');
    }

    public function entrepotArrivee()
    {
        return $this->belongsTo(Entrepot::class, 'entrepot_arrivee_id');
    }

    public function getStatutAttribute()
    {
        $etapes = $this->etapesLivraison;

        if ($etapes->isEmpty()) {
            return 'en_attente';
        }

        if ($etapes->contains('statut', 'en_cours')) {
            return 'en_cours';
        }

        // Vérifier si toutes les étapes sont "terminee"
        $toutesTerminees = $etapes->every(fn($e) => $e->statut === 'terminee');

        // Vérifier si l'une des étapes termine à la destination finale
        $destinationFinaleAtteinte = $etapes->contains(function ($e) {
            return $e->lieu_arrivee === $this->entrepotArrivee->ville && $e->statut === 'terminee';
        });

        if ($toutesTerminees && $destinationFinaleAtteinte) {
            return 'livree';
        }

        return 'en_attente';
    }

    public function genererEtapeRetraitClientFinaleSiBesoin()
    {
        $etapes = $this->etapesLivraison;

        // Si une étape client finale existe déjà, on ne la recrée pas
        $dejaCreee = $etapes->contains(fn($e) =>
            $e->est_client === true &&
            $e->lieu_depart === $this->entrepotArrivee->ville &&
            $e->lieu_arrivee === $this->entrepotArrivee->ville &&
            $e->statut !== 'annulee'
        );

        if ($dejaCreee) return;

        // Dernière étape livreur terminée
        $etapePrecedente = $etapes->where('est_client', false)
            ->where('statut', 'terminee')
            ->where('lieu_arrivee', $this->entrepotArrivee->ville)
            ->last();

        if (! $etapePrecedente) return;

        $livreur = $etapePrecedente->livreur;

        $etapeClient = EtapeLivraison::create([
            'annonce_id' => $this->id,
            'livreur_id' => $livreur->id, // pas de champ client
            'lieu_depart' => $this->entrepotArrivee->ville,
            'lieu_arrivee' => $this->entrepotArrivee->ville,
            'statut' => 'en_cours',
            'est_client' => true,
        ]);

        $box = Entrepot::where('ville', $this->entrepotArrivee->ville)
            ->first()
            ?->boxes()
            ->where('est_occupe', false)
            ->first();

        if ($box) {
            CodeBox::create([
                'box_id' => $box->id,
                'etape_livraison_id' => $etapeClient->id,
                'type' => 'retrait',
                'code_temporaire' => Str::upper(Str::random(6)),
            ]);
            $box->est_occupe = true;
            $box->save();
        }
    }


}