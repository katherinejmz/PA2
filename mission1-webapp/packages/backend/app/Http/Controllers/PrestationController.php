<?php

namespace App\Http\Controllers;

use App\Models\Prestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class PrestationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $prestations = Prestation::with(['client.utilisateur', 'prestataire.utilisateur'])
            ->when($user->role === 'client', function ($query) use ($user) {
                $query->whereHas('client', function ($q) use ($user) {
                    $q->where('utilisateur_id', $user->id);
                });
            })
            ->when($user->role === 'prestataire', function ($query) use ($user) {
                $query->whereHas('prestataire', function ($q) use ($user) {
                    $q->where('utilisateur_id', $user->id);
                });
            })
            ->orderBy('date_heure', 'desc')
            ->get();

        return response()->json($prestations);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role !== 'prestataire') {
            return response()->json(['message' => 'Seuls les prestataires peuvent publier une prestation.'], 403);
        }

        $validated = $request->validate([
            'type_prestation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_heure' => 'required|date|after:now',
            'duree_estimee' => 'nullable|integer|min:5',
            'tarif' => 'required|numeric|min:0',
        ]);

        $prestation = new Prestation($validated);
        $prestation->prestataire_id = $user->prestataire->id;
        $prestation->statut = 'disponible'; // visible par les clients
        $prestation->save();

        return response()->json([
            'message' => 'Prestation publiée avec succès.',
            'prestation' => $prestation
        ], 201);
    }


    public function show($id)
    {
        $prestation = Prestation::with(['client', 'prestataire'])->find($id);

        if (! $prestation) {
            return response()->json(['message' => 'Prestation introuvable.'], 404);
        }

        $user = Auth::user();
        if ($user->id !== $prestation->client_id && $user->id !== $prestation->prestataire_id) {
            return response()->json(['message' => 'Accès non autorisé.'], 403);
        }

        return response()->json($prestation);
    }

    public function update(Request $request, $id)
    {
        $prestation = Prestation::find($id);

        if (! $prestation) {
            return response()->json(['message' => 'Prestation introuvable.'], 404);
        }

        if ($prestation->statut !== 'en_attente') {
            return response()->json(['message' => 'Impossible de modifier une prestation non en attente.'], 403);
        }

        $user = Auth::user();
        if ($user->id !== $prestation->client_id) {
            return response()->json(['message' => 'Modification non autorisée.'], 403);
        }

        $validated = $request->validate([
            'type_prestation' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|nullable',
            'date_heure' => 'sometimes|date|after:now',
            'duree_estimee' => 'sometimes|integer|min:5',
            'tarif' => 'sometimes|numeric|min:0',
        ]);

        $prestation->update($validated);

        return response()->json(['message' => 'Prestation mise à jour.', 'prestation' => $prestation]);
    }

    public function destroy($id)
    {
        $prestation = Prestation::find($id);

        if (! $prestation) {
            return response()->json(['message' => 'Prestation introuvable.'], 404);
        }

        $user = Auth::user();
        if ($user->id !== $prestation->client_id) {
            return response()->json(['message' => 'Suppression non autorisée.'], 403);
        }

        if ($prestation->statut !== 'en_attente') {
            return response()->json(['message' => 'Impossible de supprimer une prestation déjà validée ou refusée.'], 403);
        }

        $prestation->delete();

        return response()->json(['message' => 'Prestation supprimée avec succès.']);
    }

    public function changerStatut(Request $request, $id)
    {
        $user = auth()->user();

        $prestation = Prestation::with('prestataire')->find($id);

        if (! $prestation || $user->role !== 'prestataire' || $prestation->prestataire->utilisateur_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $request->validate([
            'statut' => 'required|in:acceptée,refusée,terminée',
        ]);

        if ($prestation->statut !== 'en_attente') {
            return response()->json(['message' => 'Statut déjà défini.'], 400);
        }

        $prestation->statut = $request->statut;
        $prestation->save();

        // Créer notification pour le client (si la prestation a bien un client)
        if ($prestation->client_id) {
            Notification::create([
                'utilisateur_id' => $prestation->client->utilisateur_id ?? null,
                'titre' => 'Réponse à votre prestation',
                'contenu' => "Votre demande a été {$prestation->statut} par le prestataire.",
                'cible_type' => 'prestation',
                'cible_id' => $prestation->id,
            ]);
        }

        return response()->json(['message' => 'Statut mis à jour.', 'statut' => $prestation->statut]);
    }


    public function reserver($id)
    {
        $user = auth()->user();

        if ($user->role !== 'client') {
            return response()->json(['message' => 'Seuls les clients peuvent réserver une prestation.'], 403);
        }

        $client = \App\Models\Client::where('utilisateur_id', $user->id)->first();

        if (! $client) {
            return response()->json(['message' => 'Client introuvable.'], 404);
        }

        $prestation = \App\Models\Prestation::find($id);

        if (! $prestation) {
            return response()->json(['message' => 'Prestation introuvable.'], 404);
        }

        if ($prestation->client_id !== null) {
            return response()->json(['message' => 'Cette prestation est déjà réservée.'], 400);
        }

        $prestation->client_id = $client->id;
        $prestation->statut = 'en_attente';
        $prestation->save();

        Notification::create([
            'utilisateur_id' => $prestation->prestataire->utilisateur_id,
            'titre' => 'Nouvelle réservation',
            'contenu' => "Votre prestation '{$prestation->type_prestation}' a été réservée.",
            'cible_type' => 'prestation',
            'cible_id' => $prestation->id,
        ]);

        return response()->json(['message' => 'Réservation confirmée.']);
    }

    public function catalogue()
    {

        $prestations = Prestation::with(['prestataire.utilisateur'])
            ->whereNull('client_id')
            ->where('statut', 'disponible')
            ->orderBy('date_heure', 'asc')
            ->get();

        return response()->json($prestations);
    }

}
