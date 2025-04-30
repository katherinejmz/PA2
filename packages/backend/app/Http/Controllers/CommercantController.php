<?php

namespace App\Http\Controllers;

use App\Models\Commercant;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CommercantController extends Controller
{
    // Lister tous les commerçants
    public function index()
    {
        return Commercant::with('utilisateur', 'annonces')->get();
    }

    // Créer un commerçant
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_utilisateur' => 'required|exists:utilisateurs,id',
                'entreprise' => 'required|string|max:100',
                'adresse' => 'required|string|max:255',
                'siret' => 'required|string|max:14',
            ]);

            $commercant = Commercant::create($request->all());

            return response()->json($commercant, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher un commerçant spécifique
    public function show($id)
    {
        try {
            $commercant = Commercant::with('utilisateur', 'annonces')->findOrFail($id);
            return response()->json($commercant);
        } catch (ModelNotFoundException) {
            // Commerçant introuvable
            return response()->json(['error' => 'Commerçant non trouvé.'], 404);
        }
    }

    // Mettre à jour un commerçant
    public function update(Request $request, $id)
    {
        try {
            $commercant = Commercant::findOrFail($id);

            $request->validate([
                'entreprise' => 'sometimes|string|max:100',
                'adresse' => 'sometimes|string|max:255',
                'siret' => 'sometimes|string|max:14',
            ]);

            $commercant->update($request->only(['entreprise', 'adresse', 'siret']));

            return response()->json($commercant);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Commerçant non trouvé.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer un commerçant
    public function destroy($id)
    {
        try {
            $commercant = Commercant::findOrFail($id);
            $commercant->delete();
            return response()->json(['message' => 'Commerçant supprimé avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Commerçant non trouvé.'], 404);
        }
    }
}
