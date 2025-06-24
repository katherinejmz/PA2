<?php

namespace App\Http\Controllers;

use App\Models\EtapeLivraison;
use App\Models\CodeBox;
use App\Models\TrajetLivreur;
use App\Models\Entrepot;
use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class EtapeLivraisonController extends Controller
{
    public function show($id)
    {
        $etape = EtapeLivraison::with(['annonce', 'codes'])->findOrFail($id);
        return response()->json($etape);
    }

    // Liste des étapes pour un livreur
    public function mesEtapes()
    {
        $user = Auth::user();

        if ($user->role !== 'livreur') {
            return response()->json(['message' => 'Accès réservé aux livreurs.'], 403);
        }

        // On renvoie toutes les étapes liées à l’annonce du livreur
        $annonceIds = EtapeLivraison::where('livreur_id', $user->id)->pluck('annonce_id');

        $etapes = EtapeLivraison::with('annonce', 'codes')
            ->whereIn('annonce_id', $annonceIds)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($etapes);
    }

    // Modifier le statut d'une étape
    public function changerStatut(Request $request, $id)
    {
        $user = Auth::user();

        $etape = EtapeLivraison::findOrFail($id);

        if ($etape->livreur_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $request->validate([
            'statut' => 'required|in:en_attente,en_cours,terminee',
        ]);

        // Règle métier simple : éviter de reculer dans le statut
        $statuts = ['en_attente' => 0, 'en_cours' => 1, 'terminee' => 2];
        if ($statuts[$request->statut] < $statuts[$etape->statut]) {
            return response()->json(['message' => 'Transition invalide.'], 400);
        }

        $etape->statut = $request->statut;
        $etape->save();

        return response()->json(['message' => 'Statut mis à jour.', 'etape' => $etape]);
    }

    public function cloturerEtape($id)
    {
        $user = Auth::user();
        $etape = EtapeLivraison::with('annonce', 'codes')->findOrFail($id);

        if ($etape->livreur_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        // Vérifie si le code de dépôt est bien utilisé
        $codeDepot = $etape->codes->first(fn($c) => $c->type === 'depot');

        if (! $codeDepot || ! $codeDepot->utilise) {
            return response()->json(['message' => 'Le dépôt n’a pas encore été validé.'], 400);
        }

        // Ne pas re-clôturer une étape déjà terminée
        if ($etape->statut === 'terminee') {
            return response()->json(['message' => 'Étape déjà terminée.'], 200);
        }

        // Clôture de l’étape
        $etape->statut = 'terminee';
        $etape->save();

        $annonce = $etape->annonce;

        // 🎯 Livraison finale : générer un code retrait client si on arrive à la fin
        if (
            strcasecmp($etape->lieu_arrivee, $annonce->entrepotArrivee?->ville) === 0
            && ! $etape->est_client
        ) {
            // Vérifie si un code de retrait pour le client a déjà été créé
            $dejaCree = CodeBox::where('etape_livraison_id', $etape->id)
                ->where('type', 'retrait')
                ->exists();

            if (! $dejaCree) {
                $code = Str::upper(Str::random(6));

                $entrepot = Entrepot::where('ville', $etape->lieu_arrivee)->first();
                $box = $entrepot?->boxes()->where('est_occupe', false)->first();

                if (! $box) {
                    return response()->json(['message' => 'Aucune box dispo pour le client final.'], 400);
                }

                CodeBox::create([
                    'box_id' => $box->id,
                    'etape_livraison_id' => $etape->id,
                    'type' => 'retrait',
                    'code_temporaire' => $code,
                ]);

                $box->est_occupe = true;
                $box->save();
            }

            return response()->json(['message' => 'Étape terminée. Code client généré.']);
        }

        return response()->json(['message' => 'Étape clôturée avec succès.']);
    }



    public function validerCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'type' => 'required|in:depot,retrait',
            'etape_id' => 'required|exists:etapes_livraison,id',
        ]);

        $etape = EtapeLivraison::with('annonce')->findOrFail($request->etape_id);

        $codeBox = CodeBox::where('etape_livraison_id', $etape->id)
            ->where('type', $request->type)
            ->where('code_temporaire', $request->code)
            ->where('utilise', false)
            ->first();

        if (!$codeBox) {
            return response()->json(['message' => 'Code invalide ou déjà utilisé'], 400);
        }

        // ✅ Marquer le code comme utilisé
        $codeBox->utilise = true;
        $codeBox->save();

        // 🎯 Cas 1 : Étape client = marquer dépôt + clôturer
        if ($etape->est_client && $request->type === 'depot') {
            if ($etape->statut === 'en_cours') {
                $etape->statut = 'terminee';
                $etape->save();
            }

            return response()->json(['message' => 'Code de dépôt client validé. Étape clôturée.']);
        }

        if ($etape->est_client && $request->type === 'retrait') {
            if ($etape->statut === 'en_cours') {
                $etape->statut = 'terminee';
                $etape->save();
            }

            return response()->json(['message' => '✅ Colis retiré. Livraison terminée.']);
        }

        // 🎯 Cas 2 : Étape livreur
        if (!$etape->est_client) {
            if ($request->type === 'retrait') {
                return response()->json(['message' => 'Code de retrait validé. Vous pouvez maintenant déposer.']);
            }

            if ($request->type === 'depot') {
                $etape->statut = 'terminee';
                $etape->save();

                $annonce = $etape->annonce;

                if (
                    $etape->est_client === false &&
                    $etape->lieu_arrivee === $annonce->entrepotArrivee->ville
                ) {
                    $annonce->genererEtapeRetraitClientFinaleSiBesoin();
                }

                return response()->json(['message' => 'Colis déposé. Étape clôturée.']);
            }
        }

        return response()->json(['message' => 'Code validé.']);
    }


    public function codes($id)
    {
        $etape = EtapeLivraison::with('codes')->findOrFail($id);
        return response()->json($etape->codes);
    }

    public function etapeSuivante($id)
    {
        $etape = EtapeLivraison::findOrFail($id);

        // On cherche la prochaine étape (de la même annonce et du même livreur)
        $suivante = EtapeLivraison::where('annonce_id', $etape->annonce_id)
            ->where('livreur_id', $etape->livreur_id)
            ->where('created_at', '>', $etape->created_at)
            ->orderBy('created_at')
            ->first();

        if (! $suivante) {
            return response()->json(['message' => 'Aucune étape suivante trouvée.'], 404);
        }

        return response()->json($suivante);
    }




}
