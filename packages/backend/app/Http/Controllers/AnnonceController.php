<?php

namespace App\Http\Controllers;

use App\Models\Annonce;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class AnnonceController extends Controller
{
    private const RULE_REQUIRED_STRING = 'required|string|max:255';
    private const RULE_STRING = 'string|max:255';

    // Lister toutes les annonces
    public function index()
    {
        return Annonce::with(['client', 'commercant', 'livraison'])->get();
    }

    // Créer une annonce
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_commercant' => 'required|exists:commercants,id',
                'description' => self::RULE_REQUIRED_STRING,
                'lieu_depart' => self::RULE_REQUIRED_STRING,
                'lieu_arrivee' => self::RULE_REQUIRED_STRING,
                'prix_propose' => 'required|numeric|min:0|max:9999.99',
            ]);

            $annonce = Annonce::create($request->all());

            return response()->json($annonce, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher une annonce spécifique
    public function show($id)
    {
        try {
            $annonce = Annonce::with(['client', 'commercant', 'livraison'])->findOrFail($id);
            return response()->json($annonce);
        } catch (ModelNotFoundException) {
            // Annonce non trouvée
            return response()->json(['error' => 'Annonce non trouvée.'], 404);
        }
    }

    // Mettre à jour une annonce
    public function update(Request $request, $id)
    {
        try {
            $annonce = Annonce::findOrFail($id);

            $request->validate([
                'description' => self::RULE_STRING,
                'lieu_depart' => self::RULE_STRING,
                'lieu_arrivee' => self::RULE_STRING,
                'prix_propose' => 'sometimes|numeric|min:0|max:9999.99',
            ]);

            $annonce->update($request->only([
                'description',
                'lieu_depart',
                'lieu_arrivee',
                'prix_propose'
            ]));

            return response()->json($annonce);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Annonce non trouvée.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer une annonce
    public function destroy($id)
    {
        try {
            $annonce = Annonce::findOrFail($id);
            $annonce->delete();
            return response()->json(['message' => 'Annonce supprimée avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Annonce non trouvée.'], 404);
        }
    }
}
