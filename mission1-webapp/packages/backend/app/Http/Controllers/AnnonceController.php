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
            'photo' => 'nullable|url',
            'lieu_depart' => 'required_if:type,livraison_client,produit_livre|string|max:255',
            'lieu_arrivee' => 'required_if:type,livraison_client,produit_livre|string|max:255',
        ], [
            'lieu_depart.required_if' => 'Le lieu de départ est requis pour les livraisons.',
            'lieu_arrivee.required_if' => 'Le lieu d\'arrivée est requis pour les livraisons.',
            'photo.url' => 'L\'URL de la photo est invalide.',
        ]);

        $annonce = new Annonce($validated);

        // Association selon le rôle de l'utilisateur connecté
        if ($user->role === 'client') {
            $annonce->id_client = $user->id;
        } elseif ($user->role === 'commercant') {
            $annonce->id_commercant = $user->id;
        } elseif ($user->role === 'prestataire') {
            $annonce->id_prestataire = $user->id;
        }

        $annonce->save();

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

        $user = auth()->user();

        // Vérification d'autorisation (propriétaire)
        $estAuteur =
            ($user->role === 'client' && $annonce->id_client === $user->id) ||
            ($user->role === 'commercant' && $annonce->id_commercant === $user->id) ||
            ($user->role === 'prestataire' && $annonce->id_prestataire === $user->id);

        if (! $estAuteur && $user->role !== 'admin') {
            return response()->json(['message' => 'Action non autorisée.'], 403);
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

        $annonce->statut = 'acceptee';
        $annonce->save();

        return response()->json(['message' => 'Annonce acceptée avec succès.']);
    }

    public function mesAnnonces()
    {
        $user = Auth::user();

        // Seulement pour clients
        if ($user->role !== 'client') {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $annonces = Annonce::where('id_client', $user->id)
            ->with('livreurs') // charge les livreurs liés si existants
            ->latest()
            ->get();

        return response()->json($annonces);
    }

    public function annoncesDisponibles()
    {
        $user = auth()->user();

        if (! $user || $user->role !== 'livreur') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $annonces = \App\Models\Annonce::whereIn('type', ['livraison_client', 'produit_livre'])
            ->with(['client', 'commercant', 'livreurs'])
            ->get();

        return response()->json([
            'livreur_connecte_id' => $user->id,
            'annonces_disponibles' => $annonces
        ]);
    }

    public function mesLivraisons()
    {
        $user = auth()->user();

        if (! $user || $user->role !== 'livreur') {
            return response()->json(['message' => 'Accès réservé aux livreurs.'], 403);
        }

        $annonces = $user->livraisons() // relation belongsToMany côté Utilisateur
            ->with(['client', 'commercant'])
            ->latest()
            ->get();

        return response()->json($annonces);
    }

    public function demarrerLivraison($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->statut = 'en_cours';
        $annonce->save();

        return response()->json(['message' => 'Livraison démarrée.']);
    }

    public function marquerLivree($id)
    {
        $annonce = Annonce::findOrFail($id);
        $annonce->statut = 'livree';
        $annonce->save();

        return response()->json(['message' => 'Annonce marquée comme livrée.']);
    }

    public function changerStatut(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role !== 'livreur') {
            return response()->json(['message' => 'Seuls les livreurs peuvent mettre à jour le statut.'], 403);
        }

        $request->validate([
            'statut' => 'required|in:en_cours,livree',
        ]);

        $annonce = Annonce::with('livreurs')->find($id);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        // Vérifier que le livreur est bien affecté à cette annonce
        if (! $annonce->livreurs->contains('id', $user->id)) {
            return response()->json(['message' => 'Vous n\'êtes pas affecté à cette annonce.'], 403);
        }

        // Règles de transition : on ne peut avancer que logiquement
        if ($annonce->statut === 'acceptee' && $request->statut === 'en_cours') {
            $annonce->statut = 'en_cours';
        } elseif ($annonce->statut === 'en_cours' && $request->statut === 'livree') {
            $annonce->statut = 'livree';
        } else {
            return response()->json(['message' => 'Transition de statut invalide.'], 400);
        }

        $annonce->save();

        return response()->json(['message' => 'Statut mis à jour.', 'statut' => $annonce->statut]);
    }

}
