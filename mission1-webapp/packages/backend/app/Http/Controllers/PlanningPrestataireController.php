<?php

namespace App\Http\Controllers;

use App\Models\PlanningPrestataire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanningPrestataireController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role !== 'prestataire') {
            return response()->json(['message' => 'Accès réservé aux prestataires.'], 403);
        }

        return response()->json(
            PlanningPrestataire::where('prestataire_id', $user->id)
                ->orderBy('date_disponible')
                ->orderBy('heure_debut')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'prestataire') {
            return response()->json(['message' => 'Seuls les prestataires peuvent ajouter un planning.'], 403);
        }

        $validated = $request->validate([
            'date_disponible' => 'required|date|after_or_equal:today',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);

        $planning = PlanningPrestataire::create([
            'prestataire_id' => $user->id,
            ...$validated,
        ]);

        return response()->json(['message' => 'Créneau ajouté avec succès.', 'planning' => $planning], 201);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $planning = PlanningPrestataire::find($id);

        if (! $planning) {
            return response()->json(['message' => 'Créneau introuvable.'], 404);
        }

        if ($planning->prestataire_id !== $user->id) {
            return response()->json(['message' => 'Suppression non autorisée.'], 403);
        }

        $planning->delete();

        return response()->json(['message' => 'Créneau supprimé.']);
    }
}
