<?php

namespace App\Http\Controllers;

use App\Models\FacturePrestataire;
use App\Models\Intervention;
use App\Models\Prestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class FacturePrestataireController extends Controller
{
    public function genererFactureMensuelle($mois)
    {
        $user = Auth::user();

        if ($user->role !== 'prestataire') {
            return response()->json(['message' => 'Accès réservé aux prestataires.'], 403);
        }

        // Vérifier si facture déjà générée
        if (FacturePrestataire::where('prestataire_id', $user->id)->where('mois', $mois)->exists()) {
            return response()->json(['message' => 'Facture déjà générée pour ce mois.'], 409);
        }

        // Récupérer les prestations validées du mois donné
        $interventions = Intervention::with('prestation')
            ->whereHas('prestation', function ($query) use ($user, $mois) {
                $query->where('prestataire_id', $user->id)
                    ->whereMonth('date_heure', substr($mois, 5, 2))
                    ->whereYear('date_heure', substr($mois, 0, 4))
                    ->where('statut', 'terminée');
            })
            ->get();

        if ($interventions->isEmpty()) {
            return response()->json(['message' => 'Aucune intervention effectuée ce mois.'], 404);
        }

        $total = $interventions->sum(fn($i) => $i->prestation->tarif);

        // Générer le PDF
        $pdf = Pdf::loadView('factures.prestataire', [
            'interventions' => $interventions,
            'mois' => $mois,
            'total' => $total,
            'prestataire' => $user
        ]);

        $chemin = "factures/prestataire_{$user->id}_{$mois}.pdf";
        Storage::disk('public')->put($chemin, $pdf->output());

        $facture = FacturePrestataire::create([
            'prestataire_id' => $user->id,
            'mois' => $mois,
            'montant_total' => $total,
            'chemin_pdf' => $chemin,
        ]);

        return response()->json(['message' => 'Facture générée.', 'facture' => $facture]);
    }

    public function mesFactures()
    {
        $user = Auth::user();

        if ($user->role !== 'prestataire') {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        return response()->json(
            FacturePrestataire::where('prestataire_id', $user->id)->latest()->get()
        );
    }
}
