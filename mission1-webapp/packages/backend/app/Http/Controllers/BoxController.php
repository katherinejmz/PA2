<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    /**
     * Lister toutes les boxes (optionnellement par entrepôt).
     */
    public function index(Request $request)
    {
        $query = Box::with('entrepot');

        if ($request->has('entrepot_id')) {
            $query->where('entrepot_id', $request->entrepot_id);
        }

        return response()->json($query->get());
    }

    /**
     * Créer une nouvelle box dans un entrepôt.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'entrepot_id' => 'required|exists:entrepots,id',
            'code_box' => 'required|string|max:255|unique:boxes,code_box',
            'est_occupe' => 'boolean',
        ]);

        $box = Box::create([
            'entrepot_id' => $validated['entrepot_id'],
            'code_box' => $validated['code_box'],
            'est_occupe' => $validated['est_occupe'] ?? false,
        ]);

        return response()->json([
            'message' => 'Box créée avec succès.',
            'box' => $box
        ], 201);
    }

    /**
     * Mettre à jour une box.
     */
    public function update(Request $request, $id)
    {
        $box = Box::find($id);

        if (! $box) {
            return response()->json(['message' => 'Box introuvable.'], 404);
        }

        $validated = $request->validate([
            'code_box' => 'sometimes|string|max:255|unique:boxes,code_box,' . $box->id,
            'est_occupe' => 'sometimes|boolean',
        ]);

        $box->update($validated);

        return response()->json([
            'message' => 'Box mise à jour.',
            'box' => $box
        ]);
    }

    /**
     * Supprimer une box.
     */
    public function destroy($id)
    {
        $box = Box::find($id);

        if (! $box) {
            return response()->json(['message' => 'Box introuvable.'], 404);
        }

        $box->delete();

        return response()->json(['message' => 'Box supprimée avec succès.']);
    }
}
