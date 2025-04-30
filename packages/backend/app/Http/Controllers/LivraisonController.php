<?php

namespace App\Http\Controllers;

use App\Models\Livraison;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class LivraisonController extends Controller
{
    // Lister toutes les livraisons
    public function index()
    {
        return Livraison::with(['annonce', 'etapes', 'paiement'])->get();
    }

    // Créer une livraison
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_annonce' => 'required|exists:annonces,id',
                'date_prise_en_charge' => 'nullable|date',
                'statut' => 'nullable|string|max:50',
            ]);

            $livraison = Livraison::create($request->all());

            return response()->json($livraison, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher une livraison spécifique
    public function show($id)
    {
        try {
            $livraison = Livraison::with(['annonce', 'etapes', 'paiement'])->findOrFail($id);
            return response()->json($livraison);
        } catch (ModelNotFoundException) {
            // Livraison introuvable
            return response()->json(['error' => 'Livraison non trouvée.'], 404);
        }
    }

    // Mettre à jour une livraison
    public function update(Request $request, $id)
    {
        try {
            $livraison = Livraison::findOrFail($id);

            $request->validate([
                'date_prise_en_charge' => 'nullable|date',
                'statut' => 'sometimes|string|max:50',
            ]);

            $livraison->update($request->only(['date_prise_en_charge', 'statut']));

            return response()->json($livraison);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Livraison non trouvée.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer une livraison
    public function destroy($id)
    {
        try {
            $livraison = Livraison::findOrFail($id);
            $livraison->delete();
            return response()->json(['message' => 'Livraison supprimée avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Livraison non trouvée.'], 404);
        }
    }
}
