<?php

namespace App\Http\Controllers;

use App\Models\Colis;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColisController extends Controller
{
    /**
     * Lister les colis de l'utilisateur connecté (en tant que client).
     */
    public function index()
    {
        $user = Auth::user();

        $colis = Colis::with(['commande.annonce', 'box.entrepot'])
            ->whereHas('commande', function ($query) use ($user) {
                $query->where('client_id', $user->id);
            })
            ->get();

        return response()->json($colis);
    }

    /**
     * Créer un nouveau colis pour une commande.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'etat' => 'nullable|in:en_attente,en_depot,en_cours,livre',
            'date_depot' => 'nullable|date',
            'date_retrait' => 'nullable|date'
        ]);

        $commande = Commande::find($validated['commande_id']);

        if (! $commande || $commande->client_id !== $user->id) {
            return response()->json(['message' => 'Commande non autorisée ou introuvable.'], 403);
        }

        $colis = Colis::create([
            'commande_id' => $commande->id,
            'etat' => $validated['etat'] ?? 'en_attente',
            'date_depot' => $validated['date_depot'] ?? null,
            'date_retrait' => $validated['date_retrait'] ?? null,
        ]);

        return response()->json([
            'message' => 'Colis créé avec succès.',
            'colis' => $colis
        ], 201);
    }

    /**
     * Affecter une box existante à un colis.
     */
    public function affecterBox(Request $request, $id)
    {
        $colis = Colis::find($id);

        if (! $colis) {
            return response()->json(['message' => 'Colis introuvable.'], 404);
        }

        $validated = $request->validate([
            'box_id' => 'required|exists:boxes,id'
        ]);

        $colis->box_id = $validated['box_id'];
        $colis->etat = 'en_depot';
        $colis->date_depot = now();
        $colis->save();

        return response()->json(['message' => 'Box affectée au colis avec succès.']);
    }

    /**
     * Voir les détails d'un colis spécifique.
     */
    public function show($id)
    {
        $colis = Colis::with(['commande.annonce', 'box.entrepot'])->find($id);

        if (! $colis) {
            return response()->json(['message' => 'Colis introuvable.'], 404);
        }

        return response()->json($colis);
    }
}
