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
            'password' => 'required|string',
            'identifiant' => 'required_without:email|string',
            'email' => 'required_without:identifiant|email',
        ]);

        $user = null;

        // Si identifiant fourni → on traite comme un admin
        if ($request->filled('identifiant')) {
            $user = Utilisateur::where('identifiant', $request->identifiant)->first();

            if (! $user || $user->role !== 'admin') {
                throw ValidationException::withMessages([
                    'identifiant' => ['Accès refusé : identifiant invalide ou non administrateur.'],
                ]);
            }
        }

        // Si email fourni → on traite comme un utilisateur normal
        elseif ($request->filled('email')) {
            $user = Utilisateur::where('email', strtolower($request->email))->first();

            if (! $user || $user->role === 'admin') {
                throw ValidationException::withMessages([
                    'email' => ['Accès refusé : cet email appartient à un administrateur ou n’est pas valide.'],
                ]);
            }
        }

        // Aucun identifiant fourni
        else {
            throw ValidationException::withMessages([
                'auth' => ['Vous devez fournir un identifiant ou un email.'],
            ]);
        }

        // Vérification du mot de passe
        if (!Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Mot de passe incorrect.'],
            ]);
        }

        // Si email pas verifier; on le laisse inactif pour le moment
        /*if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Veuillez confirmer votre adresse email.'
            ], 403);
        }*/

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'identifiant' => $user->identifiant,
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
        $baseRules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:client,commercant,livreur,prestataire,admin',
            'pays' => 'nullable|string',
            'telephone' => 'nullable|string',
            'adresse_postale' => 'nullable|string',
            'nom_entreprise' => 'required_if:role,commercant|string|max:255',
            'siret' => 'required_if:role,commercant|string|max:20',
            'piece_identite' => 'required_if:role,livreur|string|max:255',
            'permis_conduire' => 'nullable|string|max:255',
            'domaine' => 'required_if:role,prestataire|string|max:255',
            'description' => 'nullable|string',
            'piece_identite_document' => 'required_if:role,livreur|file|mimes:jpg,png,pdf|max:2048',
            'permis_conduire_document' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'rgpd_consent' => 'accepted',
        ];

        // Admin utilise identifiant
        if ($request->role === 'admin') {
            $baseRules['identifiant'] = 'required|string|unique:utilisateurs,identifiant';
        } else {
            $baseRules['email'] = 'required|email|unique:utilisateurs,email';
        }

        $validated = $request->validate($baseRules);

        $utilisateur = Utilisateur::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'] ?? null,
            'identifiant' => $validated['identifiant'] ?? null,
            'password' => $validated['password'],
            'role' => $validated['role'],
            'pays' => $validated['pays'] ?? null,
            'telephone' => $validated['telephone'] ?? null,
            'adresse_postale' => $validated['adresse_postale'] ?? null,
        ]);

        switch ($utilisateur->role) {
            case 'client':
                Client::create([
                    'utilisateur_id' => $utilisateur->id,
                    'adresse' => $utilisateur->adresse_postale,
                    'telephone' => $utilisateur->telephone,
                ]);
                break;

            case 'commercant':
                Commercant::create([
                    'utilisateur_id' => $utilisateur->id,
                    'nom_entreprise' => $validated['nom_entreprise'],
                    'siret' => $validated['siret'],
                ]);
                break;

            case 'livreur':
                $pathIdentite = $request->file('piece_identite_document')->store('documents/livreurs', 'public');
                
                $pathPermis = null;
                if ($request->hasFile('permis_conduire_document')) {
                    $pathPermis = $request->file('permis_conduire_document')->store('documents/livreurs', 'public');
                }

                Livreur::create([
                    'utilisateur_id' => $utilisateur->id,
                    'piece_identite' => $validated['piece_identite'],
                    'permis_conduire' => $validated['permis_conduire'] ?? null,
                    'piece_identite_document' => $pathIdentite,
                    'permis_conduire_document' => $pathPermis,
                ]);
                break;

            case 'prestataire':
                Prestataire::create([
                    'utilisateur_id' => $utilisateur->id,
                    'domaine' => $validated['domaine'],
                    'description' => $validated['description'] ?? null,
                ]);
                break;
        }

        return response()->json([
            'token' => $utilisateur->createToken('auth_token')->plainTextToken,
            'user' => [
                'id' => $utilisateur->id,
                'nom' => $utilisateur->nom,
                'prenom' => $utilisateur->prenom,
                'email' => $utilisateur->email,
                'identifiant' => $utilisateur->identifiant,
                'role' => $utilisateur->role,
            ]
        ], 201);
    }


    public function registerAdmin(Request $request)
    {
        $validated = $request->validate([
            'identifiant' => 'required|string|unique:utilisateurs',
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $admin = Utilisateur::create([
            'identifiant' => $validated['identifiant'],
            'prenom' => $validated['prenom'],
            'nom' => $validated['nom'],
            'password' => $validated['password'],
            'role' => 'admin',
        ]);

        return response()->json([
            'message' => 'Administrateur créé avec succès',
            'admin' => $admin,
        ], 201);
    }


}
