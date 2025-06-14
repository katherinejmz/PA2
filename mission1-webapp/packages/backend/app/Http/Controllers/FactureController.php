<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FactureController extends Controller
{
    /**
     * Lister toutes les factures de l'utilisateur connecté.
     */
    public function index()
    {
        $factures = Facture::where('utilisateur_id', Auth::id())->get();

        return response()->json($factures);
    }

    /**
     * Créer une facture manuellement.
     */
    public function store(Request $request)
    {
        $request->validate([
            'montant_total' => 'required|numeric|min:0',
            'chemin_pdf' => 'required|string|max:255',
        ]);

        $facture = Facture::create([
            'utilisateur_id' => Auth::id(),
            'montant_total' => $request->montant_total,
            'chemin_pdf' => $request->chemin_pdf,
            'date_emission' => now()
        ]);

        return response()->json([
            'message' => 'Facture créée avec succès.',
            'facture' => $facture
        ], 201);
    }

    /**
     * Afficher une facture spécifique.
     */
    public function show($id)
    {
        $facture = Facture::where('utilisateur_id', Auth::id())->find($id);

        if (! $facture) {
            return response()->json(['message' => 'Facture introuvable.'], 404);
        }

        return response()->json($facture);
    }
}
