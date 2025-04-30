<?php

namespace App\Http\Controllers;

use App\Models\LivreurEtape;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class LivreurEtapeController extends Controller
{
    // Lister tous les liens livreur ↔ étape
    public function index()
    {
        return LivreurEtape::with(['livreur', 'etape'])->get();
    }

    // Affecter un livreur à une étape
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_livreur' => 'required|exists:livreurs,id',
                'id_etape' => 'required|exists:etapes_livraison,id',
                'statut' => 'nullable|string|max:50',
            ]);

            $relation = LivreurEtape::create([
                'id_livreur' => $request->id_livreur,
                'id_etape' => $request->id_etape,
                'statut' => $request->statut ?? 'affecte',
            ]);

            return response()->json($relation, 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher une affectation spécifique
    public function show($id)
    {
        try {
            $relation = LivreurEtape::with(['livreur', 'etape'])->findOrFail($id);
            return response()->json($relation);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Affectation non trouvée.'], 404);
        }
    }

    // Modifier le statut d'une affectation
    public function update(Request $request, $id)
    {
        try {
            $relation = LivreurEtape::findOrFail($id);

            $request->validate([
                'statut' => 'required|string|max:50',
            ]);

            $relation->update(['statut' => $request->statut]);

            return response()->json($relation);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Affectation non trouvée.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer une affectation
    public function destroy($id)
    {
        try {
            $relation = LivreurEtape::findOrFail($id);
            $relation->delete();
            return response()->json(['message' => 'Affectation supprimée avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Affectation non trouvée.'], 404);
        }
    }
}
