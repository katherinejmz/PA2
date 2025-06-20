<?php

namespace App\Http\Controllers;

use App\Models\EtapeLivraison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtapeLivraisonController extends Controller
{

    // Liste des étapes pour un livreur
    public function mesEtapes()
    {
        $user = Auth::user();

        if ($user->role !== 'livreur') {
            return response()->json(['message' => 'Accès réservé aux livreurs.'], 403);
        }

        $etapes = EtapeLivraison::where('livreur_id', $user->id)
            ->with('annonce')
            ->orderBy('created_at', 'desc')
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
        $etape = EtapeLivraison::with('annonce')->findOrFail($id);

        if ($etape->livreur_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        if ($etape->statut !== 'en_cours') {
            return response()->json(['message' => 'Étape non active.'], 400);
        }

        $etape->statut = 'terminee';
        $etape->save();

        // Vérifier si l'étape termine la livraison globale
        $annonce = $etape->annonce;

        if (strcasecmp($etape->lieu_arrivee, $annonce->lieu_arrivee) === 0) {
            // Livraison complète
            return response()->json(['message' => 'Livraison terminée, destination finale atteinte.']);
        }

        // Sinon : la livraison continue depuis ce point
        return response()->json([
            'message' => 'Étape terminée. Livraison partielle enregistrée.',
            'annonce_reouverte' => true,
            'point_actuel' => $etape->lieu_arrivee,
        ]);
    }

}
