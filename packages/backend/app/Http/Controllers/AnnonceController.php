<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    public function index()
    {
        return response()->json(Annonce::with(['client', 'commercant', 'prestataire', 'livreurs'])->get());
    }

    public function show($id)
    {
        $annonce = Annonce::with(['client', 'commercant', 'prestataire', 'livreurs'])->find($id);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        return response()->json($annonce);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'type' => 'required|in:livraison_client,produit_livre,service',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix_propose' => 'required|numeric|min:0',
            'photo' => 'nullable|string',
            'lieu_depart' => 'nullable|string',
            'lieu_arrivee' => 'nullable|string',
        ]);

        $annonceData = array_merge($validated, ['id_client' => $user->id]);

        // Assigner les rôles selon le type d’annonce
        if ($validated['type'] === 'produit_livre') {
            $annonceData['id_commercant'] = $user->id;
        } elseif ($validated['type'] === 'service') {
            $annonceData['id_prestataire'] = $user->id;
        }

        $annonce = Annonce::create($annonceData);

        return response()->json(['message' => 'Annonce créée avec succès.', 'annonce' => $annonce], 201);
    }

    public function update(Request $request, $id)
    {
        $annonce = Annonce::find($id);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        $validated = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'prix_propose' => 'sometimes|numeric|min:0',
            'photo' => 'nullable|string',
            'lieu_depart' => 'nullable|string',
            'lieu_arrivee' => 'nullable|string',
        ]);

        $annonce->update($validated);

        return response()->json(['message' => 'Annonce mise à jour.', 'annonce' => $annonce]);
    }

    public function destroy($id)
    {
        $annonce = Annonce::find($id);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        $annonce->delete();

        return response()->json(['message' => 'Annonce supprimée avec succès.']);
    }
}
