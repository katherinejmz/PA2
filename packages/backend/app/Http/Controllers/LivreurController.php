<?php

namespace App\Http\Controllers;

use App\Models\Livreur;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class LivreurController extends Controller
{
    // Lister tous les livreurs
    public function index()
    {
        return Livreur::with(['utilisateur', 'etapes', 'communications', 'evaluations'])->get();
    }

    // Créer un livreur
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_utilisateur' => 'required|exists:utilisateurs,id',
                'vehicule' => 'required|string|max:100',
                'note_moyenne' => 'nullable|numeric|min:0|max:5',
                'nombre_livraisons' => 'nullable|integer|min:0',
            ]);

            $livreur = Livreur::create($request->all());

            return response()->json($livreur, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher un livreur spécifique
    public function show($id)
    {
        try {
            $livreur = Livreur::with(['utilisateur', 'etapes', 'communications', 'evaluations'])->findOrFail($id);
            return response()->json($livreur);
        } catch (ModelNotFoundException) {
            // Livreur non trouvé
            return response()->json(['error' => 'Livreur non trouvé.'], 404);
        }
    }

    // Mettre à jour un livreur
    public function update(Request $request, $id)
    {
        try {
            $livreur = Livreur::findOrFail($id);

            $request->validate([
                'vehicule' => 'sometimes|string|max:100',
                'note_moyenne' => 'nullable|numeric|min:0|max:5',
                'nombre_livraisons' => 'nullable|integer|min:0',
            ]);

            $livreur->update($request->only(['vehicule', 'note_moyenne', 'nombre_livraisons']));

            return response()->json($livreur);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Livreur non trouvé.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer un livreur
    public function destroy($id)
    {
        try {
            $livreur = Livreur::findOrFail($id);
            $livreur->delete();
            return response()->json(['message' => 'Livreur supprimé avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Livreur non trouvé.'], 404);
        }
    }
}
