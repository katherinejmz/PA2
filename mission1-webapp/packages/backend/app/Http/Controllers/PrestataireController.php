<?php

namespace App\Http\Controllers;

use App\Models\Prestataire;
use Illuminate\Http\Request;

class PrestataireController extends Controller
{

    public function index()
    {
        $prestataires = Prestataire::with('utilisateur')
            ->where('valide', true)
            ->get();

        return response()->json($prestataires);
    }

    public function show($id)
    {
        $prestataire = Prestataire::where('utilisateur_id', $id)->first();

        if (! $prestataire) {
            return response()->json(['message' => 'Prestataire introuvable.'], 404);
        }

        return response()->json($prestataire);
    }

    public function update(Request $request, $id)
    {
        $prestataire = Prestataire::where('utilisateur_id', $id)->first();

        if (! $prestataire) {
            return response()->json(['message' => 'Prestataire introuvable.'], 404);
        }

        $validated = $request->validate([
            'domaine' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $prestataire->update($validated);

        return response()->json([
            'message' => 'Profil prestataire mis à jour avec succès.',
            'prestataire' => $prestataire
        ]);
    }
}
