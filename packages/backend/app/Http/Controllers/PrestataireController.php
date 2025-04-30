<?php

namespace App\Http\Controllers;

use App\Models\Prestataire;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class PrestataireController extends Controller
{
    // Lister tous les prestataires
    public function index()
    {
        return Prestataire::with(['utilisateur', 'evaluations'])->get();
    }

    // Créer un prestataire
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_utilisateur' => 'required|exists:utilisateurs,id',
                'type_service' => 'required|string|max:100',
                'tarif_horaire' => 'required|numeric|min:0|max:999.99',
            ]);

            $prestataire = Prestataire::create($request->all());

            return response()->json($prestataire, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher un prestataire spécifique
    public function show($id)
    {
        try {
            $prestataire = Prestataire::with(['utilisateur', 'evaluations'])->findOrFail($id);
            return response()->json($prestataire);
        } catch (ModelNotFoundException) {
            // Prestataire non trouvé
            return response()->json(['error' => 'Prestataire non trouvé.'], 404);
        }
    }

    // Mettre à jour un prestataire
    public function update(Request $request, $id)
    {
        try {
            $prestataire = Prestataire::findOrFail($id);

            $request->validate([
                'type_service' => 'sometimes|string|max:100',
                'tarif_horaire' => 'sometimes|numeric|min:0|max:999.99',
            ]);

            $prestataire->update($request->only(['type_service', 'tarif_horaire']));

            return response()->json($prestataire);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Prestataire non trouvé.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer un prestataire
    public function destroy($id)
    {
        try {
            $prestataire = Prestataire::findOrFail($id);
            $prestataire->delete();
            return response()->json(['message' => 'Prestataire supprimé avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Prestataire non trouvé.'], 404);
        }
    }
}
