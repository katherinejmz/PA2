<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annonce;
use App\Models\TrajetLivreur;
use App\Models\EtapeLivraison;
use App\Models\Entrepot;
use App\Models\CodeBox;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AnnonceController extends Controller
{
    public function index(Request $request)
    {
        $query = Annonce::with(['client', 'commercant', 'entrepotDepart', 'entrepotArrivee']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'ILIKE', "%$search%")
                  ->orWhere('description', 'ILIKE', "%$search%");
            });
        }

        if ($request->filled('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('prix_propose', $request->sort);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return response()->json($query->get());
    }

    public function show($id)
    {
        $annonce = Annonce::with([
            'client',
            'commercant',
            'entrepotDepart',
            'entrepotArrivee',
            'etapesLivraison.codes'
        ])->find($id);

        if (! $annonce) {
            return response()->json(['message' => 'Annonce introuvable.'], 404);
        }

        return response()->json($annonce);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Déterminer les règles dynamiquement selon le rôle
        $rules = [
            'type' => 'required|in:livraison_client,produit_livre',
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'prix_propose' => 'required|numeric|min:0',
            'photo' => 'nullable|url',
            'entrepot_depart_id' => 'required|exists:entrepots,id',
        ];

        // Si client → il faut entrepot_arrivee_id
        if ($user->role === 'client') {
            $rules['entrepot_arrivee_id'] = 'required|exists:entrepots,id';
        }

        $validated = $request->validate($rules);

        $annonce = new Annonce($validated);

        if ($user->role === 'client') {
            $annonce->id_client = $user->id;
        } elseif ($user->role === 'commercant') {
            $annonce->id_commercant = $user->id;
            $annonce->entrepot_arrivee_id = null; // ne doit pas être défini au départ
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

        if (
            $annonce->type === 'produit_livre' &&
            $annonce->id_client !== null
        ) {
            return response()->json(['message' => 'Cette annonce a déjà été réservée et ne peut plus être modifiée.'], 403);
        }

        $validated = $request->validate([
            'titre' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'prix_propose' => 'sometimes|numeric|min:0',
            'photo' => 'nullable|string',
            'entrepot_depart_id' => 'sometimes|exists:entrepots,id',
            'entrepot_arrivee_id' => 'sometimes|exists:entrepots,id',
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

        $estAuteur =
            ($user->role === 'client' && $annonce->id_client === $user->id) ||
            ($user->role === 'commercant' && $annonce->id_commercant === $user->id);

        if (! $estAuteur && $user->role !== 'admin') {
            return response()->json(['message' => 'Action non autorisée.'], 403);
        }

        if (
            $annonce->type === 'produit_livre' &&
            $annonce->id_client !== null
        ) {
            return response()->json(['message' => 'Cette annonce a déjà été réservée et ne peut plus être modifiée.'], 403);
        }

        $annonce->delete();

        return response()->json(['message' => 'Annonce supprimée avec succès.']);
    }

    public function mesAnnonces()
    {
        $user = Auth::user();

        if (!in_array($user->role, ['client', 'commercant'])) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $annonces = Annonce::with([
            'etapesLivraison.livreur',
            'entrepotDepart',
            'entrepotArrivee'
        ])
        ->where(function ($q) use ($user) {
            if ($user->role === 'client') {
                $q->where('id_client', $user->id);
            } elseif ($user->role === 'commercant') {
                $q->where('id_commercant', $user->id);
            }
        })
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

        $trajets = TrajetLivreur::with('entrepotDepart')->where('livreur_id', $user->id)->get();

        if ($trajets->isEmpty()) {
            return response()->json(['annonces_disponibles' => []]);
        }

        $annonces = Annonce::with(['etapesLivraison', 'entrepotDepart', 'entrepotArrivee'])
            ->whereIn('type', ['livraison_client', 'produit_livre'])
            ->get();

        $disponibles = [];

        foreach ($annonces as $annonce) {
            // ❗ Spécifique aux annonces produit_livre : ignorer si pas encore réservée
            if (
                $annonce->type === 'produit_livre' &&
                (
                    ! $annonce->id_client ||
                    ! $annonce->entrepotArrivee
                )
            ) {
                continue;
            }

            $etapes = $annonce->etapesLivraison;

            // ⚠️ S'il y a déjà des étapes, il ne faut AUCUNE étape livreur en cours
            if ($etapes->count() > 0) {
                $enCours = $etapes->first(fn($e) => $e->statut !== 'terminee' && $e->est_client === false);
                if ($enCours) continue;
            }

            // Déterminer la ville de départ actuelle
            $depart_actuel = $annonce->entrepotDepart?->ville ?? '';
            $lastStep = $etapes->where('statut', 'terminee')->last();
            if ($lastStep) {
                $depart_actuel = $lastStep->lieu_arrivee;
            }

            // Vérifier compatibilité avec un trajet du livreur
            $compatible = $trajets->first(fn($trajet) =>
                $trajet->entrepotDepart &&
                strcasecmp($trajet->entrepotDepart->ville, $depart_actuel) === 0
            );

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

        $annonce = Annonce::with(['etapesLivraison', 'entrepotDepart', 'entrepotArrivee'])->findOrFail($id);

        $enCours = $annonce->etapesLivraison()->where('statut', '!=', 'terminee')->exists();

        if ($enCours) {
            return response()->json(['message' => 'Cette annonce est déjà en cours de livraison.'], 400);
        }

        $trajets = TrajetLivreur::with(['entrepotDepart', 'entrepotArrivee'])
            ->where('livreur_id', $user->id)
            ->get();

        if ($trajets->isEmpty()) {
            return response()->json(['message' => 'Aucun trajet disponible.'], 400);
        }

        // Déterminer le point de départ
        $depart_actuel = $annonce->entrepotDepart?->ville ?? '';

        // On récupère la dernière étape terminée **selon le type d’annonce**
        $lastStep = $annonce->etapesLivraison()
            ->where('statut', 'terminee')
            ->when(
                $annonce->type === 'produit_livre',
                fn($q) => $q->where('est_commercant', false)
            )
            ->orderByDesc('created_at')
            ->first();

        if ($lastStep) {
            $depart_actuel = $lastStep->lieu_arrivee;
        }
        
        // Vérifier si un trajet correspond
        $trajetCompatible = $trajets->first(fn($trajet) =>
            $trajet->entrepotDepart && strcasecmp($trajet->entrepotDepart->ville, $depart_actuel) === 0
        );
        
        if (is_null($trajetCompatible)) {
            logger()->error("❌ Aucun trajet trouvé pour départ_actuel = $depart_actuel");
            logger()->info("📦 Trajets disponibles : " . json_encode($trajets));
        }

        if (! $trajetCompatible || ! $trajetCompatible->entrepotArrivee) {
            return response()->json(['message' => 'Aucun trajet compatible avec l’annonce.'], 400);
        }

        $destination = $trajetCompatible->entrepotArrivee->ville;
        $villeFinale = $annonce->entrepotArrivee?->ville;

        $isDerniereEtape = strcasecmp($destination, $villeFinale) === 0;

        $etapesCreees = [];

        if ($depart_actuel === $annonce->entrepotDepart->ville) {
            if ($annonce->type === 'produit_livre') {
                // Étape dépôt du commerçant
                $etapeCommercant = EtapeLivraison::create([
                    'annonce_id' => $annonce->id,
                    'livreur_id' => $user->id,
                    'lieu_depart' => $depart_actuel,
                    'lieu_arrivee' => $depart_actuel,
                    'statut' => 'en_cours',
                    'est_client' => false,
                    'est_commercant' => true,
                ]);

                $entrepot = Entrepot::where('ville', $depart_actuel)->first();
                $box = $entrepot?->boxes()->where('est_occupe', false)->first();
                if (!$box) return response()->json(['message' => 'Aucune box disponible pour le commerçant.'], 400);

                CodeBox::create([
                    'box_id' => $box->id,
                    'etape_livraison_id' => $etapeCommercant->id,
                    'type' => 'depot',
                    'code_temporaire' => Str::upper(Str::random(6)),
                ]);

                $box->est_occupe = true;
                $box->save();

                $etapesCreees[] = $etapeCommercant;
            } else {
                // Étape dépôt du client
                $etapeClient = EtapeLivraison::create([
                    'annonce_id' => $annonce->id,
                    'livreur_id' => $user->id,
                    'lieu_depart' => $depart_actuel,
                    'lieu_arrivee' => $depart_actuel,
                    'statut' => 'en_cours',
                    'est_client' => true,
                ]);

                $entrepot = Entrepot::where('ville', $depart_actuel)->first();
                $box = $entrepot?->boxes()->where('est_occupe', false)->first();
                if (!$box) return response()->json(['message' => 'Aucune box disponible pour le client.'], 400);

                CodeBox::create([
                    'box_id' => $box->id,
                    'etape_livraison_id' => $etapeClient->id,
                    'type' => 'depot',
                    'code_temporaire' => Str::upper(Str::random(6)),
                ]);

                $box->est_occupe = true;
                $box->save();

                $etapesCreees[] = $etapeClient;
            }
        }

        // 🚚 Étape pour le livreur (retrait + dépôt OU retrait seul si destination finale)
        $etapeLivreur = EtapeLivraison::create([
            'annonce_id' => $annonce->id,
            'livreur_id' => $user->id,
            'lieu_depart' => $depart_actuel,
            'lieu_arrivee' => $destination,
            'statut' => 'en_cours',
            'est_client' => false,
        ]);

        $entrepot = Entrepot::where('ville', $depart_actuel)->first();
        $box = $entrepot?->boxes()->where('est_occupe', false)->first();
        if (!$box) return response()->json(['message' => 'Aucune box disponible pour le livreur.'], 400);

        // retrait obligatoire
        CodeBox::create([
            'box_id' => $box->id,
            'etape_livraison_id' => $etapeLivreur->id,
            'type' => 'retrait',
            'code_temporaire' => Str::upper(Str::random(6)),
        ]);
       
        CodeBox::create([
            'box_id' => $box->id,
            'etape_livraison_id' => $etapeLivreur->id,
            'type' => 'depot',
            'code_temporaire' => Str::upper(Str::random(6)),
        ]);
        

        $box->est_occupe = true;
        $box->save();

        $etapesCreees[] = $etapeLivreur;

        return response()->json([
            'message' => 'Annonce acceptée, étapes créées.',
            'etapes' => $etapesCreees,
        ]);
    }

    public function reserverAnnonce(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->role !== 'client') {
            return response()->json(['message' => 'Seuls les clients peuvent réserver.'], 403);
        }

        $request->validate([
            'entrepot_arrivee_id' => 'required|exists:entrepots,id',
        ]);

        $annonce = Annonce::findOrFail($id);

        if ($annonce->type !== 'produit_livre') {
            return response()->json(['message' => 'Cette annonce ne peut pas être réservée.'], 400);
        }

        if ($annonce->id_client !== null || $annonce->entrepot_arrivee_id !== null) {
            return response()->json(['message' => 'Annonce déjà réservée.'], 400);
        }

        $annonce->id_client = $user->id;
        $annonce->entrepot_arrivee_id = $request->entrepot_arrivee_id;
        $annonce->save();

        return response()->json(['message' => 'Annonce réservée avec succès.']);
    }

}
