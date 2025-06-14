<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Commande;
use App\Models\Annonce;

class CommandeController extends Controller
{
    /**
     * Lister toutes les commandes de l'utilisateur connecté.
     */
    public function index()
    {
        $utilisateur = Auth::user();

        $commandes = Commande::with('annonce')
            ->where('client_id', $utilisateur->id)
            ->get();

        return response()->json($commandes);
    }

    /**
     * Créer une nouvelle commande (achat d'une annonce).
     */
    public function store(Request $request)
    {
        $utilisateur = Auth::user();

        if ($utilisateur->role !== 'client') {
            return response()->json(['message' => 'Seuls les clients peuvent commander.'], 403);
        }

        $validated = $request->validate([
            'annonce_id' => 'required|exists:annonces,id',
            'montant' => 'required|numeric|min:0',
        ]);

        $annonce = Annonce::find($validated['annonce_id']);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        $commande = Commande::create([
            'annonce_id' => $annonce->id,
            'client_id' => $utilisateur->id,
            'montant' => $annonce->prix_propose,
            'statut' => 'en_attente',
            'achete_le' => now(),
        ]);

        return response()->json([
            'message' => 'Commande enregistrée.',
            'commande' => $commande
        ], 201);
    }

    /**
     * Afficher une commande spécifique.
     */
    public function show($id)
    {
        $commande = Commande::with('annonce')->find($id);

        if (! $commande) {
            return response()->json(['message' => 'Commande introuvable.'], 404);
        }

        return response()->json($commande);
    }
}
