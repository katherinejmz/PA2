<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UtilisateurController extends Controller
{
    /**
     * Lister tous les utilisateurs.
     */
    public function index()
    {
        return response()->json(Utilisateur::all());
    }

    /**
     * Afficher un utilisateur spécifique.
     */
    public function show($id)
    {
        $utilisateur = Utilisateur::find($id);

        if (! $utilisateur) {
            return response()->json(['message' => 'Utilisateur introuvable.'], 404);
        }

        return response()->json($utilisateur);
    }

    /**
     * Modifier un utilisateur.
     */
    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::find($id);

        if (! $utilisateur) {
            return response()->json(['message' => 'Utilisateur introuvable.'], 404);
        }

        $validated = $request->validate([
            'nom' => ['sometimes', 'string', 'max:32', 'regex:/^[\pL\s\-]+$/u'],
            'prenom' => ['sometimes', 'string', 'max:32', 'regex:/^[\pL\s\-]+$/u'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('utilisateurs')->ignore($utilisateur->id)
            ],
            'pays' => ['nullable', 'string'],
            'telephone' => ['nullable', 'string'],
            'adresse_postale' => ['nullable', 'string'],
        ], [
            'email.unique' => 'Cet email est déjà utilisé.',
            'nom.regex' => 'Le nom ne peut contenir que des lettres, des espaces et des tirets.',
            'prenom.regex' => 'Le prénom ne peut contenir que des lettres, des espaces et des tirets.',
        ]);

        $utilisateur->update($validated);

        return response()->json(['message' => 'Utilisateur mis à jour avec succès.', 'utilisateur' => $utilisateur]);
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy($id)
    {
        $utilisateur = Utilisateur::find($id);

        if (! $utilisateur) {
            return response()->json(['message' => 'Utilisateur introuvable.'], 404);
        }

        $utilisateur->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
    }
}

