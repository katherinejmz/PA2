<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\TrajetLivreur;
use App\Models\EtapeLivraison;
use App\Models\Entrepot;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        $query = Annonce::with(['client', 'commercant', 'prestataire', 'livreurs']);

        // Filtrer par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Recherche mot-clé (titre ou description)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'ILIKE', "%$search%")
                  ->orWhere('description', 'ILIKE', "%$search%");
            });
        }

        // Tri par prix proposé
        if ($request->filled('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('prix_propose', $request->sort);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $annonce = Annonce::with(['client', 'commercant', 'prestataire', 'livreurs'])->find($id);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        return response()->json($annonce);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'type' => 'required|in:livraison_client,produit_livre,service',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix_propose' => 'required|numeric|min:0',
            'photo' => 'nullable|url',
            'lieu_depart' => 'required_if:type,livraison_client,produit_livre|string|max:255',
            'lieu_arrivee' => 'required_if:type,livraison_client,produit_livre|string|max:255',
        ], [
            'lieu_depart.required_if' => 'Le lieu de départ est requis pour les livraisons.',
            'lieu_arrivee.required_if' => 'Le lieu d\'arrivée est requis pour les livraisons.',
            'photo.url' => 'L\'URL de la photo est invalide.',
        ]);

        $annonce = new Annonce($validated);

        // Association selon le rôle de l'utilisateur connecté
        if ($user->role === 'client') {
            $annonce->id_client = $user->id;
        } elseif ($user->role === 'commercant') {
            $annonce->id_commercant = $user->id;
        } elseif ($user->role === 'prestataire') {
            $annonce->id_prestataire = $user->id;
        }

        $annonce->save();

        return response()->json([
            'message' => 'Annonce créée avec succès.',
            'annonce' => $annonce
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $annonce = Annonce::find($id);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        $validated = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'prix_propose' => 'sometimes|numeric|min:0',
            'photo' => 'nullable|string',
            'lieu_depart' => 'nullable|string',
            'lieu_arrivee' => 'nullable|string',
        ]);

        $annonce->update($validated);

        return response()->json(['message' => 'Annonce mise à jour.', 'annonce' => $annonce]);
    }

    public function destroy($id)
    {
        $annonce = Annonce::find($id);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        $user = auth()->user();

        // Vérification d'autorisation (propriétaire)
        $estAuteur =
            ($user->role === 'client' && $annonce->id_client === $user->id) ||
            ($user->role === 'commercant' && $annonce->id_commercant === $user->id) ||
            ($user->role === 'prestataire' && $annonce->id_prestataire === $user->id);

        if (! $estAuteur && $user->role !== 'admin') {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        $annonce->delete();

        return response()->json(['message' => 'Annonce supprimée avec succès.']);
    }



    public function mesAnnonces()
    {
        $user = Auth::user();

        if ($user->role !== 'client') {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $annonces = Annonce::where('id_client', $user->id)
            ->with(['etapesLivraison.livreur'])
            ->latest()
            ->get();

        return response()->json($annonces);
    }


    public function annoncesDisponibles()
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'livreur') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $trajets = TrajetLivreur::where('livreur_id', $user->id)->get();

        if ($trajets->isEmpty()) {
            return response()->json(['annonces_disponibles' => []]);
        }

        $annonces = Annonce::with(['etapesLivraison'])
            ->whereIn('type', ['livraison_client', 'produit_livre'])
            ->get();

        $disponibles = [];

        foreach ($annonces as $annonce) {
            $depart_actuel = $annonce->lieu_depart;

            $etapes = $annonce->etapesLivraison()->get();

            $lastStep = $etapes->filter(function ($etape) {
                return $etape->statut === 'terminee';
            })->last();

            if ($lastStep) {
                $depart_actuel = $lastStep->lieu_arrivee;
            }

            // Vérifie s’il existe au moins un trajet compatible
            $compatible = $trajets->first(function ($trajet) use ($depart_actuel) {
                return strcasecmp($trajet->ville_depart, $depart_actuel) === 0;
            });

            if ($compatible) {
                $disponibles[] = $annonce;
            }
        }

        return response()->json([
            'livreur_connecte_id' => $user->id,
            'annonces_disponibles' => $disponibles
        ]);
    }


    public function accepterAnnonce(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'livreur') {
            return response()->json(['message' => 'Accès refusé'], 403);
        }

        $annonce = Annonce::with('etapesLivraison')->findOrFail($id);
        $trajets = TrajetLivreur::where('livreur_id', $user->id)->get();

        if ($trajets->isEmpty()) {
            return response()->json(['message' => 'Aucun trajet disponible.'], 400);
        }

        // Point de départ actuel de l'annonce
        $depart_actuel = $annonce->lieu_depart;
        $etapes = $annonce->etapesLivraison()->get();

        $lastStep = $etapes->filter(function ($etape) {
            return $etape->statut === 'terminee';
        })->last();

        if ($lastStep) {
            $depart_actuel = $lastStep->lieu_arrivee;
        }

        // Trouver un trajet dont la ville_depart correspond à ce point
        $trajetCompatible = $trajets->first(function ($trajet) use ($depart_actuel) {
            return strcasecmp($trajet->ville_depart, $depart_actuel) === 0;
        });

        if (! $trajetCompatible) {
            return response()->json(['message' => 'Aucun trajet compatible avec l annonce.'], 400);
        }

        $destination = $trajetCompatible->ville_arrivee;

        // Si le trajet ne va pas jusqu'au bout de l'annonce, chercher un entrepôt proche
        if (strcasecmp($destination, $annonce->lieu_arrivee) !== 0) {
            $entrepot = Entrepot::all()->sortBy(function ($e) use ($destination) {
                return levenshtein(strtolower($e->ville), strtolower($destination));
            })->first();

            if ($entrepot) {
                $destination = $entrepot->ville;
            }
        }

        // Créer l'étape
        EtapeLivraison::create([
            'annonce_id' => $annonce->id,
            'livreur_id' => $user->id,
            'lieu_depart' => $depart_actuel,
            'lieu_arrivee' => $destination,
            'statut' => 'en_cours',
        ]);

        return response()->json(['message' => 'Annonce acceptée et étape créée.']);
    }



}
