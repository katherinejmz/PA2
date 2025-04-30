<?php

namespace App\Http\Controllers;

use App\Models\EtapeLivraison;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class EtapeLivraisonController extends Controller
{
    private const RULE_REQUIRED_STRING = 'required|string|max:255';
    private const RULE_SOMETIMES_STRING = 'sometimes|string|max:255';

    // Lister toutes les étapes
    public function index()
    {
        return EtapeLivraison::with(['livraison', 'livreurs', 'communications'])->get();
    }

    // Créer une nouvelle étape de livraison
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_livraison' => 'required|exists:livraisons,id',
                'ordre' => 'required|integer|min:1',
                'lieu_depart' => self::RULE_REQUIRED_STRING,
                'lieu_arrivee' => self::RULE_REQUIRED_STRING,
                'statut' => 'nullable|string|max:50',
                'date_prise_en_charge' => 'nullable|date',
            ]);

            $etape = EtapeLivraison::create($request->all());

            return response()->json($etape, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher une étape spécifique
    public function show($id)
    {
        try {
            $etape = EtapeLivraison::with(['livraison', 'livreurs', 'communications'])->findOrFail($id);
            return response()->json($etape);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Étape non trouvée.'], 404);
        }
    }

    // Mettre à jour une étape
    public function update(Request $request, $id)
    {
        try {
            $etape = EtapeLivraison::findOrFail($id);

            $request->validate([
                'ordre' => 'sometimes|integer|min:1',
                'lieu_depart' => self::RULE_SOMETIMES_STRING,
                'lieu_arrivee' => self::RULE_SOMETIMES_STRING,
                'statut' => 'sometimes|string|max:50',
                'date_prise_en_charge' => 'nullable|date',
            ]);

            $etape->update($request->only([
                'ordre', 'lieu_depart', 'lieu_arrivee', 'statut', 'date_prise_en_charge'
            ]));

            return response()->json($etape);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Étape non trouvée.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer une étape
    public function destroy($id)
    {
        try {
            $etape = EtapeLivraison::findOrFail($id);
            $etape->delete();
            return response()->json(['message' => 'Étape supprimée avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Étape non trouvée.'], 404);
        }
    }
}
