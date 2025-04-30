<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    // Lister tous les clients
    public function index()
    {
        return Client::with('utilisateur', 'annonces', 'evaluations')->get();
    }

    // Créer un nouveau client
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_utilisateur' => 'required|exists:utilisateurs,id',
                'adresse' => 'required|string|max:255',
                'telephone' => 'required|string|max:20',
                'date_inscription' => 'required|date',
            ]);

            $client = Client::create($request->all());

            return response()->json($client, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher un client spécifique
    public function show($id)
    {
        try {
            $client = Client::with('utilisateur', 'annonces', 'evaluations')->findOrFail($id);
            return response()->json($client);
        } catch (ModelNotFoundException) {
            // Client non trouvé
            return response()->json(['error' => 'Client non trouvé.'], 404);
        }
    }

    // Mettre à jour un client
    public function update(Request $request, $id)
    {
        try {
            $client = Client::findOrFail($id);

            $request->validate([
                'adresse' => 'sometimes|string|max:255',
                'telephone' => 'sometimes|string|max:20',
                'date_inscription' => 'sometimes|date',
            ]);

            $client->update($request->all());

            return response()->json($client);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Client non trouvé.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer un client
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return response()->json(['message' => 'Client supprimé avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Client non trouvé.'], 404);
        }
    }
}
