<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Models\Prestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InterventionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $interventions = Intervention::with(['prestation.client', 'prestation.prestataire'])
            ->whereHas('prestation', function ($query) use ($user) {
                if ($user->role === 'prestataire') {
                    $query->where('prestataire_id', $user->id);
                } elseif ($user->role === 'client') {
                    $query->where('client_id', $user->id);
                }
            })->latest()->get();

        return response()->json($interventions);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'prestataire') {
            return response()->json(['message' => 'Seuls les prestataires peuvent valider une intervention.'], 403);
        }

        $validated = $request->validate([
            'prestation_id' => 'required|exists:prestations,id',
            'statut_final' => 'required|in:effectuée,non effectuée',
        ]);

        $prestation = Prestation::find($validated['prestation_id']);

        if ($prestation->prestataire_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        if ($prestation->statut !== 'acceptée' && $prestation->statut !== 'terminée') {
            return response()->json(['message' => 'Prestation non éligible.'], 400);
        }

        $intervention = Intervention::create([
            'prestation_id' => $prestation->id,
            'statut_final' => $validated['statut_final'],
        ]);

        // Mettre à jour le statut de la prestation
        $prestation->statut = 'terminée';
        $prestation->save();

        return response()->json(['message' => 'Intervention enregistrée.', 'intervention' => $intervention], 201);
    }

    public function update(Request $request, $id)
    {
        $intervention = Intervention::with('prestation')->find($id);
        $user = Auth::user();

        if (! $intervention) {
            return response()->json(['message' => 'Intervention introuvable.'], 404);
        }

        if ($intervention->prestation->client_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $validated = $request->validate([
            'note' => 'nullable|integer|min:1|max:5',
            'commentaire_client' => 'nullable|string|max:1000',
        ]);

        $intervention->update($validated);

        return response()->json(['message' => 'Intervention mise à jour.', 'intervention' => $intervention]);
    }
}
