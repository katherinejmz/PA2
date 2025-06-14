<?php

namespace App\Http\Controllers;

use App\Models\Livreur;
use Illuminate\Http\Request;

class LivreurController extends Controller
{
    public function show($id)
    {
        $livreur = Livreur::where('utilisateur_id', $id)->first();

        if (! $livreur) {
            return response()->json(['message' => 'Livreur introuvable.'], 404);
        }

        return response()->json($livreur);
    }

    public function update(Request $request, $id)
    {
        $livreur = Livreur::where('utilisateur_id', $id)->first();

        if (! $livreur) {
            return response()->json(['message' => 'Livreur introuvable.'], 404);
        }

        $validated = $request->validate([
            'piece_identite' => 'required|string|max:255',
            'permis_conduire' => 'nullable|string|max:255',
        ]);

        $livreur->update($validated);

        return response()->json([
            'message' => 'Profil livreur mis à jour avec succès.',
            'livreur' => $livreur
        ]);
    }
}
