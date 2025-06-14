<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    /**
     * Lister les évaluations faites par l'utilisateur connecté.
     */
    public function index()
    {
        $evaluations = Evaluation::where('client_id', Auth::id())->get();
        return response()->json($evaluations);
    }

    /**
     * Lister les évaluations reçues par un utilisateur.
     */
    public function showByUtilisateur($utilisateur_id)
    {
        $evaluations = Evaluation::where('utilisateur_id', $utilisateur_id)->get();
        return response()->json($evaluations);
    }

    /**
     * Créer une nouvelle évaluation.
     */
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'annonce_id' => 'required|exists:annonces,id',
            'note' => 'required|integer|min:1|max:5',
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $evaluation = Evaluation::create([
            'client_id' => Auth::id(),
            'utilisateur_id' => $request->utilisateur_id,
            'annonce_id' => $request->annonce_id,
            'note' => $request->note,
            'commentaire' => $request->commentaire,
        ]);

        return response()->json([
            'message' => 'Évaluation enregistrée avec succès.',
            'evaluation' => $evaluation
        ], 201);
    }

    /**
     * Voir une évaluation spécifique.
     */
    public function show($id)
    {
        $evaluation = Evaluation::find($id);

        if (! $evaluation) {
            return response()->json(['message' => 'Évaluation introuvable.'], 404);
        }

        return response()->json($evaluation);
    }
}
