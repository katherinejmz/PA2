<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Utilisateur;
use App\Models\Commercant;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = Utilisateur::where('email', strtolower($request->email))->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants sont invalides.'],
            ]);
        }

        // L'ID du commerçant est l'ID utilisateur si role === commerçant
        $idCommercant = $user->role === 'commercant' ? $user->id : null;

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'role' => $user->role,
                'id_commercant' => $idCommercant,
            ],
        ]);
    }


    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Déconnexion réussie.']);
        }

        return response()->json(['message' => 'Aucun utilisateur authentifié.'], 401);
    }


    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:utilisateurs,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:client,commercant,prestataire,livreur,admin',
            'pays' => 'nullable|string',
            'telephone' => 'nullable|string',
            'adresse_postale' => 'nullable|string',
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => strtolower($validated['email']),
            'password' => $validated['password'], // hashé automatiquement via mutator
            'role' => $validated['role'],
            'pays' => $validated['pays'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'adresse_postale' => $validated['adresse_postale'] ?? null,
        ]);

        return response()->json([
            'token' => $utilisateur->createToken('auth_token')->plainTextToken,
            'user' => [
                'id' => $utilisateur->id,
                'nom' => $utilisateur->nom,
                'prenom' => $utilisateur->prenom,
                'email' => $utilisateur->email,
                'role' => $utilisateur->role,
            ]
        ], 201);
    }



}
