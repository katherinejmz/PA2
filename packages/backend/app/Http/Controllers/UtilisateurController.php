<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UtilisateurController extends Controller
{
    // Lister tous les utilisateurs
    public function index()
    {
        return Utilisateur::all();
    }

    // Créer un nouvel utilisateur
    public function store(Request $request)
    {
        try {
            $request->merge([
                'email' => strtolower($request->email)
            ]);

            $request->validate([
                'nom' => ['required', 'string', 'max:32', 'regex:/^[\pL\s\-]+$/u'],
                'prenom' => ['required', 'string', 'max:32', 'regex:/^[\pL\s\-]+$/u'],
                'email' => 'required|email:rfc,dns|max:255|unique:utilisateurs,email',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:32',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
                ],
                'role' => 'required|string|in:client,commercant,livreur,prestataire',
            ], [
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.regex' => 'Le mot de passe doit contenir une majuscule, une minuscule, un chiffre et un caractère spécial.',
                'email.unique' => 'Cet email est déjà utilisé.',
            ]);

            $utilisateur = Utilisateur::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return response()->json($utilisateur, 201);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Afficher un utilisateur spécifique
    public function show($id)
    {
        try {
            $utilisateur = Utilisateur::findOrFail($id);
            return response()->json($utilisateur);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Utilisateur non trouvé.'], 404);
        }
    }

    // Mettre à jour un utilisateur
    public function update(Request $request, $id)
    {
        try {
            $utilisateur = Utilisateur::findOrFail($id);

            if ($request->filled('email')) {
                $request->merge(['email' => strtolower($request->email)]);
            }

            $request->validate([
                'nom' => ['sometimes', 'string', 'max:32', 'regex:/^[\pL\s\-]+$/u'],
                'prenom' => ['sometimes', 'string', 'max:32', 'regex:/^[\pL\s\-]+$/u'],
                'email' => 'sometimes|email:rfc,dns|max:255|unique:utilisateurs,email,' . $id,
                'password' => [
                    'sometimes',
                    'string',
                    'min:8',
                    'max:32',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
                ],
                'role' => 'sometimes|string|in:client,commercant,livreur,prestataire',
            ], [
                'password.regex' => 'Le mot de passe doit contenir une majuscule, une minuscule, un chiffre et un caractère spécial.',
            ]);

            if ($request->filled('password')) {
                $request->merge(['password' => Hash::make($request->password)]);
            }

            $utilisateur->update($request->only([
                'nom', 'prenom', 'email', 'password', 'role'
            ]));

            return response()->json($utilisateur);

        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Utilisateur non trouvé.'], 404);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        try {
            $utilisateur = Utilisateur::findOrFail($id);
            $utilisateur->delete();
            return response()->json(['message' => 'Utilisateur supprimé avec succès.']);
        } catch (ModelNotFoundException) {
            return response()->json(['error' => 'Utilisateur non trouvé.'], 404);
        }
    }
}

