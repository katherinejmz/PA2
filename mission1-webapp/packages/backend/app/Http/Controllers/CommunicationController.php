<?php

namespace App\Http\Controllers;

use App\Models\Communication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationController extends Controller
{
    /**
     * Lister les messages entre l'utilisateur connecté et un autre utilisateur.
     */
    public function index(Request $request, $destinataire_id)
    {
        $query = Communication::where(function ($q) use ($destinataire_id) {
                $q->where('expediteur_id', Auth::id())
                  ->where('destinataire_id', $destinataire_id);
            })
            ->orWhere(function ($q) use ($destinataire_id) {
                $q->where('expediteur_id', $destinataire_id)
                  ->where('destinataire_id', Auth::id());
            });

        if ($request->filled('annonce_id')) {
            $query->where('annonce_id', $request->annonce_id);
        }

        $messages = $query->orderBy('created_at')->get();

        return response()->json($messages);
    }

    /**
     * Envoyer un message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'destinataire_id' => 'required|exists:utilisateurs,id',
            'annonce_id' => 'nullable|exists:annonces,id',
            'message' => 'required|string|max:2000',
        ]);

        $message = Communication::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $request->destinataire_id,
            'annonce_id' => $request->annonce_id,
            'message' => $request->message,
        ]);

        return response()->json([
            'message' => 'Message envoyé avec succès.',
            'data' => $message
        ], 201);
    }

    /**
     * Marquer un message comme lu.
     */
    public function markAsRead($id)
    {
        $message = Communication::find($id);

        if (! $message || $message->destinataire_id !== Auth::id()) {
            return response()->json(['message' => 'Message introuvable ou non autorisé.'], 403);
        }

        $message->update(['lu_at' => now()]);

        return response()->json(['message' => 'Message marqué comme lu.']);
    }
}
