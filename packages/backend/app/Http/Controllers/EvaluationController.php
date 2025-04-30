<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class EvaluationController extends Controller
{
    // Lister toutes les évaluations
    public function index()
    {
        return Evaluation::with(['client', 'livreur', 'prestataire'])->get();
    }

    // Créer une évaluation
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_client' => 'required|exists:clients,id',
                'id_livreur' => 'nullable|exists:livreurs,id',
                'id_prestataire' => 'nullable|exists:prestataires,id',
                'note' => 'required|integer|min:1|max:5',
                'commentaire' => 'nullable|string|max:1000',
            ]);

            $evaluation = Evaluation::create($request->all());

            return response()->json($evaluation, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher une évaluation spécifique
    public function show($id)
    {
        try {
            $evaluation = Evaluation::with(['client', 'livreur', 'prestataire'])->findOrFail($id);
            return response()->json($evaluation);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Évaluation non trouvée.'], 404);
        }
    }

    // Mettre à jour une évaluation
    public function update(Request $request, $id)
    {
        try {
            $evaluation = Evaluation::findOrFail($id);

            $request->validate([
                'note' => 'sometimes|integer|min:1|max:5',
                'commentaire' => 'nullable|string|max:1000',
            ]);

            $evaluation->update($request->only(['note', 'commentaire']));

            return response()->json($evaluation);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Évaluation non trouvée.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer une évaluation
    public function destroy($id)
    {
        try {
            $evaluation = Evaluation::findOrFail($id);
            $evaluation->delete();
            return response()->json(['message' => 'Évaluation supprimée avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Évaluation non trouvée.'], 404);
        }
    }
}
