<?php

namespace App\Http\Controllers;

use App\Models\EtapeLivraison;
use App\Models\Colis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtapeLivraisonController extends Controller
{
    /**
     * Lister les étapes d’un colis.
     */
    public function index($colis_id)
    {
        $etapes = EtapeLivraison::where('colis_id', $colis_id)
                    ->orderBy('date_etape')
                    ->get();

        return response()->json($etapes);
    }

    /**
     * Créer une nouvelle étape de livraison.
     */
    public function store(Request $request)
    {
        $request->validate([
            'colis_id' => 'required|exists:colis,id',
            'statut' => 'required|in:préparation,déposé,en transit,en attente,livré,échoué',
            'commentaire' => 'nullable|string',
        ]);

        $etape = EtapeLivraison::create([
            'colis_id' => $request->colis_id,
            'statut' => $request->statut,
            'commentaire' => $request->commentaire,
            'date_etape' => now(),
        ]);

        return response()->json([
            'message' => 'Étape ajoutée avec succès.',
            'etape' => $etape
        ], 201);
    }

    /**
     * Afficher une étape spécifique (optionnel).
     */
    public function show($id)
    {
        $etape = EtapeLivraison::find($id);

        if (! $etape) {
            return response()->json(['message' => 'Étape introuvable.'], 404);
        }

        return response()->json($etape);
    }
}
