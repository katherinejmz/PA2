<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Lister les notifications de l'utilisateur connecté.
     */
    public function index()
    {
        $notifications = Notification::where('utilisateur_id', Auth::id())
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        return response()->json($notifications);
    }

    /**
     * Marquer une notification comme lue.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
                                    ->where('utilisateur_id', Auth::id())
                                    ->first();

        if (! $notification) {
            return response()->json(['message' => 'Notification introuvable.'], 404);
        }

        $notification->update(['lu_at' => now()]);

        return response()->json(['message' => 'Notification marquée comme lue.']);
    }

    /**
     * Créer une notification (ex. : par admin ou test manuel).
     */
    public function store(Request $request)
    {
        $request->validate([
            'utilisateur_id' => 'required|exists:utilisateurs,id',
            'titre' => 'required|string|max:255',
            'contenu' => 'nullable|string',
            'cible_type' => 'nullable|string',
            'cible_id' => 'nullable|integer',
        ]);

        $notification = Notification::create($request->all());

        return response()->json([
            'message' => 'Notification créée.',
            'notification' => $notification
        ], 201);
    }
}
