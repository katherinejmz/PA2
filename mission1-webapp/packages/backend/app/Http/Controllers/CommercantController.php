<?php

namespace App\Http\Controllers;

use App\Models\Commercant;
use Illuminate\Http\Request;

class CommercantController extends Controller
{
    public function show($id)
    {
        $commercant = Commercant::where('utilisateur_id', $id)->first();

        if (! $commercant) {
            return response()->json(['message' => 'Commerçant introuvable.'], 404);
        }

        return response()->json($commercant);
    }

    public function update(Request $request, $id)
    {
        $commercant = Commercant::where('utilisateur_id', $id)->first();

        if (! $commercant) {
            return response()->json(['message' => 'Commerçant introuvable.'], 404);
        }

        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'siret' => 'required|string|max:20',
        ]);

        $commercant->update($validated);

        return response()->json([
            'message' => 'Profil commerçant mis à jour avec succès.',
            'commercant' => $commercant
        ]);
    }
}
