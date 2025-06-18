<?php

namespace App\Http\Controllers\Api;

use App\Models\Utilisateur;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmailVerificationController extends Controller
{
    public function verify($id, Request $request)
    {
        $user = Utilisateur::findOrFail($id);

        if (! hash_equals((string) $request->route('hash'), sha1($user->email))) {
            return response()->json(['message' => 'Lien invalide.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email déjà vérifié.'], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Votre adresse email a été vérifiée avec succès.'], 200);
    }
}
