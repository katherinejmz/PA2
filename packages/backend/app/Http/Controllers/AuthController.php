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

        // Injecter l'id_commercant si le rôle correspond
        if ($user->role === 'commercant') {
            $commercant = Commercant::where('id_utilisateur', $user->id)->first();
            if ($commercant) {
                $user->id_commercant = $commercant->id;
            }
        }

        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'user' => [
                'id' => $user->id,
                'nom' => $user->nom,
                'prenom' => $user->prenom,
                'email' => $user->email,
                'role' => $user->role,
                'id_commercant' => $user->role === 'commercant'
                    ? Commercant::where('id_utilisateur', $user->id)->value('id')
                    : null
            ],
        ]);
        
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnexion réussie.']);
    }
}
