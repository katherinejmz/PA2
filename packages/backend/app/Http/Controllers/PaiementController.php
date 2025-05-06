<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paiement;

class PaiementController extends Controller
{
    /**
     * Lister les paiements de l'utilisateur connecté.
     */
    public function index()
    {
        $utilisateur = Auth::user();

        $paiements = Paiement::where('utilisateur_id', $utilisateur->id)->get();

        return response()->json($paiements);
    }

    /**
     * Créer un nouveau paiement.
     */
    public function store(Request $request)
    {
        $utilisateur = Auth::user();

        $validated = $request->validate([
            'commande_id' => 'nullable|exists:commandes,id',
            'montant' => 'required|numeric|min:0',
            'sens' => 'required|in:credit,debit',
            'type' => 'required|in:stripe,portefeuille,virement,remboursement',
            'reference' => 'nullable|string|max:255'
        ]);

        $paiement = Paiement::create([
            'utilisateur_id' => $utilisateur->id,
            'commande_id' => $validated['commande_id'] ?? null,
            'montant' => $validated['montant'],
            'sens' => $validated['sens'],
            'type' => $validated['type'],
            'reference' => $validated['reference'] ?? null,
        ]);

        return response()->json([
            'message' => 'Paiement enregistré.',
            'paiement' => $paiement
        ], 201);
    }

    /**
     * Afficher un paiement spécifique.
     */
    public function show($id)
    {
        $paiement = Paiement::find($id);

        if (! $paiement) {
            return response()->json(['message' => 'Paiement introuvable.'], 404);
        }

        return response()->json($paiement);
    }
}
