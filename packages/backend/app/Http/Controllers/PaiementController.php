<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class PaiementController extends Controller
{
    // Lister tous les paiements
    public function index()
    {
        return Paiement::with('livraison', 'facture')->get();
    }

    // Créer un nouveau paiement
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_livraison' => 'required|exists:livraisons,id',
                'montant' => 'required|numeric|min:0|max:10000',
                'date' => 'required|date',
                'statut' => 'nullable|string|max:50',
            ]);

            $paiement = Paiement::create([
                'id_livraison' => $request->id_livraison,
                'montant' => $request->montant,
                'date' => $request->date,
                'statut' => $request->statut ?? 'en attente',
            ]);

            return response()->json($paiement, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher un paiement spécifique
    public function show($id)
    {
        try {
            $paiement = Paiement::with('livraison', 'facture')->findOrFail($id);
            return response()->json($paiement);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Paiement non trouvé.'], 404);
        }
    }

    // Mettre à jour un paiement
    public function update(Request $request, $id)
    {
        try {
            $paiement = Paiement::findOrFail($id);

            $request->validate([
                'montant' => 'sometimes|numeric|min:0|max:10000',
                'date' => 'sometimes|date',
                'statut' => 'sometimes|string|max:50',
            ]);

            $paiement->update($request->only(['montant', 'date', 'statut']));

            return response()->json($paiement);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Paiement non trouvé.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer un paiement
    public function destroy($id)
    {
        try {
            $paiement = Paiement::findOrFail($id);
            $paiement->delete();
            return response()->json(['message' => 'Paiement supprimé avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Paiement non trouvé.'], 404);
        }
    }
}
