<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UtilisateurController extends Controller
{
    public function index()
    {
        return Utilisateur::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:utilisateurs',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        return Utilisateur::create($validated);
    }

    public function show($id)
    {
        return Utilisateur::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $utilisateur = Utilisateur::findOrFail($id);

        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $utilisateur->update($request->only(['nom', 'prenom', 'email', 'password', 'role']));

        return $utilisateur;
    }

    public function destroy($id)
    {
        $utilisateur = Utilisateur::findOrFail($id);
        $utilisateur->delete();

        return response()->json(['message' => 'Utilisateur supprimé']);
    }
}

