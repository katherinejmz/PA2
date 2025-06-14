<?php

namespace App\Http\Controllers;

use App\Models\Portefeuille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortefeuilleController extends Controller
{
    /**
     * Afficher le solde du portefeuille de l'utilisateur connecté.
     */
    public function show()
    {
        $portefeuille = Portefeuille::firstOrCreate(
            ['utilisateur_id' => Auth::id()],
            ['solde' => 0]
        );

        return response()->json([
            'solde' => $portefeuille->solde
        ]);
    }

    /**
     * Créditer le portefeuille (ajout d'argent).
     */
    public function credit(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0.01'
        ]);

        $portefeuille = Portefeuille::firstOrCreate(
            ['utilisateur_id' => Auth::id()],
            ['solde' => 0]
        );

        $portefeuille->increment('solde', $request->montant);

        return response()->json([
            'message' => 'Portefeuille crédité.',
            'nouveau_solde' => $portefeuille->solde
        ]);
    }

    /**
     * Débiter le portefeuille (retrait d'argent, optionnel).
     */
    public function debit(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric|min:0.01'
        ]);

        $portefeuille = Portefeuille::firstOrCreate(
            ['utilisateur_id' => Auth::id()],
            ['solde' => 0]
        );

        if ($portefeuille->solde < $request->montant) {
            return response()->json(['message' => 'Solde insuffisant.'], 400);
        }

        $portefeuille->decrement('solde', $request->montant);

        return response()->json([
            'message' => 'Portefeuille débité.',
            'nouveau_solde' => $portefeuille->solde
        ]);
    }
}
