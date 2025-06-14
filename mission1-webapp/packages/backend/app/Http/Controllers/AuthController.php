<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\Utilisateur;
use App\Models\Commercant;
use App\Models\Client;
use App\Models\Livreur;
use App\Models\Prestataire;


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
                'pays' => $user->pays,
                'telephone' => $user->telephone,
                'adresse_postale' => $user->adresse_postale,
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
            'role' => 'required|in:client,commercant,livreur',
            'pays' => 'nullable|string',
            'telephone' => 'nullable|string',
            'adresse_postale' => 'nullable|string',
            'nom_entreprise' => 'required_if:role,commercant|string|max:255',
            'siret' => 'required_if:role,commercant|string|max:20',
            'piece_identite' => 'required_if:role,livreur|string|max:255',
            'permis_conduire' => 'nullable|string|max:255',
            'domaine' => 'required_if:role,prestataire|string|max:255',
            'description' => 'nullable|string',
        ]);

        $utilisateur = Utilisateur::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => strtolower($validated['email']),
            'password' => $validated['password'], // hash via mutator
            'role' => $validated['role'],
            'pays' => $validated['pays'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'adresse_postale' => $validated['adresse_postale'] ?? null,
        ]);

        if ($utilisateur->role === 'client') {
            Client::create([
                'utilisateur_id' => $utilisateur->id,
                'adresse' => $utilisateur->adresse_postale,
                'telephone' => $utilisateur->telephone,
            ]);
        }

        if ($utilisateur->role === 'commercant') {
            Commercant::create([
                'utilisateur_id' => $utilisateur->id,
                'nom_entreprise' => $validated['nom_entreprise'],
                'siret' => $validated['siret'],
            ]);
        }

        if ($utilisateur->role === 'livreur') {
            Livreur::create([
                'utilisateur_id' => $utilisateur->id,
                'piece_identite' => $validated['piece_identite'],
                'permis_conduire' => $validated['permis_conduire'] ?? null,
            ]);
        }

        if ($utilisateur->role === 'prestataire') {
            Prestataire::create([
                'utilisateur_id' => $utilisateur->id,
                'domaine' => $validated['domaine'],
                'description' => $validated['description'] ?? null,
            ]);
        }

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
