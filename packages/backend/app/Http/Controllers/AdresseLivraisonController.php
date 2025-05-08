<?php

namespace App\Http\Controllers;

use App\Models\AdresseLivraison;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdresseLivraisonController extends Controller
{
    /**
     * Enregistrer une adresse de livraison pour une commande.
     */
    public function store(Request $request)
    {
        $request->validate([
            'commande_id'   => 'required|exists:commandes,id',
            'adresse'       => 'required|string|max:255',
            'ville'         => 'required|string|max:100',
            'code_postal'   => 'required|string|max:20',
            'pays'          => 'required|string|max:100',
            'instructions'  => 'nullable|string',
        ]);

        $commande = Commande::find($request->commande_id);

        // Vérifie que la commande appartient bien au client connecté
        if (! $commande || $commande->client_id !== Auth::id()) {
            return response()->json(['message' => 'Commande non autorisée ou introuvable.'], 403);
        }

        // Vérifie qu'une adresse n'existe pas déjà
        if ($commande->adresseLivraison) {
            return response()->json(['message' => 'Une adresse existe déjà pour cette commande.'], 409);
        }

        $adresse = AdresseLivraison::create([
            'commande_id'   => $commande->id,
            'adresse'       => $request->adresse,
            'ville'         => $request->ville,
            'code_postal'   => $request->code_postal,
            'pays'          => $request->pays,
            'instructions'  => $request->instructions,
        ]);

        return response()->json([
            'message' => 'Adresse de livraison enregistrée.',
            'adresse' => $adresse
        ], 201);
    }
}
