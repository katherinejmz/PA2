<?php

namespace App\Http\Controllers;

use App\Models\Entrepot;
use Illuminate\Http\Request;

class EntrepotController extends Controller
{
    /**
     * Lister tous les entrepôts.
     */
    public function index()
    {
        return response()->json(Entrepot::all());
    }

    /**
     * Créer un nouvel entrepôt.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:20',
            'pays' => 'required|string|max:255'
        ]);

        $entrepot = Entrepot::create($validated);

        return response()->json([
            'message' => 'Entrepôt créé avec succès.',
            'entrepot' => $entrepot
        ], 201);
    }

    /**
     * Afficher un entrepôt spécifique.
     */
    public function show($id)
    {
        $entrepot = Entrepot::find($id);

        if (! $entrepot) {
            return response()->json(['message' => 'Entrepôt introuvable.'], 404);
        }

        return response()->json($entrepot);
    }

    /**
     * Modifier un entrepôt.
     */
    public function update(Request $request, $id)
    {
        $entrepot = Entrepot::find($id);

        if (! $entrepot) {
            return response()->json(['message' => 'Entrepôt introuvable.'], 404);
        }

        $validated = $request->validate([
            'nom' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string|max:255',
            'ville' => 'sometimes|string|max:255',
            'code_postal' => 'sometimes|string|max:20',
            'pays' => 'sometimes|string|max:255'
        ]);

        $entrepot->update($validated);

        return response()->json([
            'message' => 'Entrepôt mis à jour.',
            'entrepot' => $entrepot
        ]);
    }

    /**
     * Supprimer un entrepôt.
     */
    public function destroy($id)
    {
        $entrepot = Entrepot::find($id);

        if (! $entrepot) {
            return response()->json(['message' => 'Entrepôt introuvable.'], 404);
        }

        $entrepot->delete();

        return response()->json(['message' => 'Entrepôt supprimé avec succès.']);
    }
}
