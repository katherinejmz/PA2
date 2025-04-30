<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class FactureController extends Controller
{
    // Lister toutes les factures
    public function index()
    {
        return Facture::with('paiement')->get();
    }

    // Créer une nouvelle facture
    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_paiement' => 'required|exists:paiements,id',
                'pdf_facture' => 'required|string|max:255',
                'date_emission' => 'required|date',
            ]);

            $facture = Facture::create($request->all());

            return response()->json($facture, 201);

        } catch (ValidationException $e) {
            // Erreurs de validation
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher une facture spécifique
    public function show($id)
    {
        try {
            $facture = Facture::with('paiement')->findOrFail($id);
            return response()->json($facture);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Facture non trouvée.'], 404);
        }
    }

    // Mettre à jour une facture
    public function update(Request $request, $id)
    {
        try {
            $facture = Facture::findOrFail($id);

            $request->validate([
                'pdf_facture' => 'sometimes|string|max:255',
                'date_emission' => 'sometimes|date',
            ]);

            $facture->update($request->only(['pdf_facture', 'date_emission']));

            return response()->json($facture);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Facture non trouvée.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer une facture
    public function destroy($id)
    {
        try {
            $facture = Facture::findOrFail($id);
            $facture->delete();
            return response()->json(['message' => 'Facture supprimée avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Facture non trouvée.'], 404);
        }
    }
}
