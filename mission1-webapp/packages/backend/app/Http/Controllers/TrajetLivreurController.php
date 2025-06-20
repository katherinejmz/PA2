<?php

namespace App\Http\Controllers;

use App\Models\TrajetLivreur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrajetLivreurController extends Controller
{
    // Lister les trajets du livreur connecté
    public function index()
    {
        $user = Auth::user();
        $trajets = TrajetLivreur::where('livreur_id', $user->id)->get();
        return response()->json($trajets);
    }

    // Ajouter un trajet
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'ville_depart' => 'required|string|max:255',
            'ville_arrivee' => 'required|string|max:255',
            'disponible_du' => 'nullable|date',
            'disponible_au' => 'nullable|date|after_or_equal:disponible_du',
        ]);

        $trajet = TrajetLivreur::create(array_merge($validated, ['livreur_id' => $user->id]));
        return response()->json(['message' => 'Trajet enregistré.', 'trajet' => $trajet]);
    }

    // Supprimer un trajet
    public function destroy($id)
    {
        $trajet = TrajetLivreur::findOrFail($id);
        if ($trajet->livreur_id !== Auth::id()) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }
        $trajet->delete();
        return response()->json(['message' => 'Trajet supprimé.']);
    }
}
