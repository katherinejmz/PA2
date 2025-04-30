<?php

namespace App\Http\Controllers;

use App\Models\Communication;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class CommunicationController extends Controller
{
    // Lister toutes les communications
    public function index()
    {
        return Communication::with(['etape', 'livreur'])->get();
    }

    // Créer une communication
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_etape' => 'required|exists:etapes_livraison,id',
                'id_livreur' => 'required|exists:livreurs,id',
                'message' => 'required|string|max:1000',
            ]);

            $communication = Communication::create($request->all());

            return response()->json($communication, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher une communication spécifique
    public function show($id)
    {
        try {
            $communication = Communication::with(['etape', 'livreur'])->findOrFail($id);
            return response()->json($communication);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Communication non trouvée.'], 404);
        }
    }

    // Mettre à jour une communication
    public function update(Request $request, $id)
    {
        try {
            $communication = Communication::findOrFail($id);

            $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $communication->update($request->only('message'));

            return response()->json($communication);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Communication non trouvée.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer une communication
    public function destroy($id)
    {
        try {
            $communication = Communication::findOrFail($id);
            $communication->delete();
            return response()->json(['message' => 'Message supprimé avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Communication non trouvée.'], 404);
        }
    }
}
