<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    // Retourne tous les clients
    public function index()
    {
        return response()->json(Client::all());
    }

    // Affiche un client par utilisateur_id (lié au profil utilisateur)
    public function show($id)
    {
        $client = Client::where('utilisateur_id', $id)->first();

        if (!$client) {
            return response()->json(['message' => 'Client introuvable.'], 404);
        }

        return response()->json($client);
    }

    // Met à jour un client (seulement téléphone ou adresse)
    public function update(Request $request, $id)
    {
        $client = Client::where('utilisateur_id', $id)->first();

        if (!$client) {
            return response()->json(['message' => 'Client introuvable.'], 404);
        }

        $request->validate([
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:20'
        ]);

        $client->update([
            'adresse' => $request->input('adresse', $client->adresse),
            'telephone' => $request->input('telephone', $client->telephone)
        ]);

        return response()->json(['message' => 'Client mis à jour.', 'client' => $client]);
    }
}