<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        $query = Annonce::with(['client', 'commercant', 'prestataire', 'livreurs']);

        // Filtrer par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Recherche mot-clé (titre ou description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'ILIKE', "%$search%")
                  ->orWhere('description', 'ILIKE', "%$search%");
            });
        }

        // Tri par prix proposé
        if ($request->filled('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('prix_propose', $request->sort);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return response()->json($query->get());
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

        if ($validated['type'] === 'produit_livre') {
            $annonceData['id_commercant'] = $user->id;
        } elseif ($validated['type'] === 'service') {
            $annonceData['id_prestataire'] = $user->id;
        }

        $annonce = Annonce::create($annonceData);

        return response()->json([
            'message' => 'Annonce créée avec succès.',
            'annonce' => $annonce
        ], 201);
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


    public function accepter(Request $request, $id)
    {
        $user = auth()->user();

        if (! $user || $user->role !== 'livreur') {
            return response()->json(['message' => 'Seuls les livreurs peuvent accepter une annonce.'], 403);
        }

        $annonce = Annonce::find($id);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        if (!in_array($annonce->type, ['livraison_client', 'produit_livre'])) {
            return response()->json(['message' => 'Ce type d\'annonce ne peut pas être accepté par un livreur.'], 400);
        }

        $annonce->livreurs()->syncWithoutDetaching([$user->id]);

        return response()->json(['message' => 'Annonce acceptée avec succès.']);
    }

}
