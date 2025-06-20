<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ColisController;
use App\Http\Controllers\BoxController;
use App\Http\Controllers\EntrepotController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\EtapeLivraisonController;
use App\Http\Controllers\PortefeuilleController;
use App\Http\Controllers\AdresseLivraisonController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommercantController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\PrestataireController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\PlanningPrestataireController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\FacturePrestataireController;
use App\Http\Controllers\StatAdminController;
use App\Http\Controllers\Api\EmailVerificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\TrajetLivreurController;

// Authentification
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Route de confirmation de l'email (sans authentification)
Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->middleware('signed')
    ->name('verification.verify');

// ADMIN uniquement
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Creation d'un Admin
    Route::post('/admin/register', [AuthController::class, 'registerAdmin']);
    
    // Dashboard
    Route::get('/admin/statistiques', [StatAdminController::class, 'index']);

    // Gestion des utilisateurs
    Route::post('/utilisateurs', [UtilisateurController::class, 'store']);
    Route::patch('/utilisateurs/{id}', [UtilisateurController::class, 'update']);

    // Notifications
    Route::post('/notifications', [NotificationController::class, 'store']);

    // Entrepôts
    Route::post('/entrepots', [EntrepotController::class, 'store']);
    Route::patch('/entrepots/{id}', [EntrepotController::class, 'update']);
    Route::delete('/entrepots/{id}', [EntrepotController::class, 'destroy']);

    // Boxes
    Route::post('/boxes', [BoxController::class, 'store']);
    Route::patch('/boxes/{id}', [BoxController::class, 'update']);
    Route::delete('/boxes/{id}', [BoxController::class, 'destroy']);

    // Annonces
    Route::patch('/annonces/{id}', [AnnonceController::class, 'update']);
    Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);

    // Client
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{id}', [ClientController::class, 'show']);
    Route::patch('/clients/{id}', [ClientController::class, 'update']);

});

// CLIENT uniquement
Route::middleware(['auth:sanctum', 'role:admin,client'])->group(function () {
    Route::get('/clients', [ClientController::class, 'index']);
    Route::get('/clients/{id}', [ClientController::class, 'show']);
    Route::patch('/clients/{id}', [ClientController::class, 'update']);
    Route::post('/evaluations', [EvaluationController::class, 'store']);
    Route::post('/factures', [FactureController::class, 'store']);
    
});

// LIVREUR uniquement
Route::middleware(['auth:sanctum', 'role:admin,livreur'])->group(function () {
    Route::post('/colis', [ColisController::class, 'store']);
    Route::patch('/colis/{id}/box', [ColisController::class, 'affecterBox']);
    Route::get('/livreurs/{id}', [LivreurController::class, 'show']);
    Route::patch('/livreurs/{id}', [LivreurController::class, 'update']);
    Route::get('/annonces-disponibles', [AnnonceController::class, 'annoncesDisponibles']);
    Route::post('/annonces/{id}/accepter', [AnnonceController::class, 'accepterAnnonce']);
    Route::get('/mes-trajets', [TrajetLivreurController::class, 'index']);
    Route::post('/mes-trajets', [TrajetLivreurController::class, 'store']);
    Route::delete('/mes-trajets/{id}', [TrajetLivreurController::class, 'destroy']);
});

// COMMERCANT uniquement
Route::middleware(['auth:sanctum', 'role:admin,commercant'])->group(function () {
    Route::get('/commercants/{id}', [CommercantController::class, 'show']);
    Route::patch('/commercants/{id}', [CommercantController::class, 'update']);
});

// PRESTATAIRE uniquement
Route::middleware(['auth:sanctum', 'role:admin,prestataire'])->group(function () {
    Route::get('/prestataires/{id}', [PrestataireController::class, 'show']);
    Route::patch('/prestataires/{id}', [PrestataireController::class, 'update']);
    Route::post('/prestations', [PrestationController::class, 'store']);
});

// Routes accessibles à tout utilisateur connecté
Route::middleware('auth:sanctum')->group(function () {

    // Utilisateurs
    Route::get('/utilisateurs', [UtilisateurController::class, 'index']);
    Route::get('/utilisateurs/{id}', [UtilisateurController::class, 'show']);
    Route::patch('/utilisateurs/{id}', [UtilisateurController::class, 'update']);
    Route::delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy']);


    // Annonces
    Route::get('/annonces', [AnnonceController::class, 'index']);
    Route::get('/annonces/{id}', [AnnonceController::class, 'show']);
    Route::get('/mes-annonces', [AnnonceController::class, 'mesAnnonces']);
    Route::post('/annonces', [AnnonceController::class, 'store']);
    Route::patch('/annonces/{id}', [AnnonceController::class, 'update']);
    Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);
    
    // Commandes
    Route::get('/commandes', [CommandeController::class, 'index']);
    Route::get('/commandes/{id}', [CommandeController::class, 'show']);
    Route::post('/commandes', [CommandeController::class, 'store']);

    // Paiements
    Route::get('/paiements', [PaiementController::class, 'index']);
    Route::get('/paiements/{id}', [PaiementController::class, 'show']);
    Route::post('/paiements', [PaiementController::class, 'store']);

    // Colis
    Route::get('/colis', [ColisController::class, 'index']);
    Route::get('/colis/{id}', [ColisController::class, 'show']);

    // Boxes
    Route::get('/boxes', [BoxController::class, 'index']);
    Route::patch('/boxes/{id}', [BoxController::class, 'update']);
    Route::delete('/boxes/{id}', [BoxController::class, 'destroy']);

    // Entrepôts
    Route::get('/entrepots', [EntrepotController::class, 'index']);
    Route::get('/entrepots/{id}', [EntrepotController::class, 'show']);
    Route::patch('/entrepots/{id}', [EntrepotController::class, 'update']);
    Route::delete('/entrepots/{id}', [EntrepotController::class, 'destroy']);

    // Factures
    Route::get('/factures', [FactureController::class, 'index']);
    Route::get('/factures/{id}', [FactureController::class, 'show']);

    // Évaluations
    Route::get('/evaluations', [EvaluationController::class, 'index']);
    Route::get('/evaluations/cible/{utilisateur_id}', [EvaluationController::class, 'showByCible']);
    Route::get('/evaluations/{id}', [EvaluationController::class, 'show']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/lire', [NotificationController::class, 'markAsRead']);

    // Messagerie
    Route::get('/communications/{destinataire_id}', [CommunicationController::class, 'index']);
    Route::post('/communications', [CommunicationController::class, 'store']);
    Route::post('/communications/{id}/lire', [CommunicationController::class, 'markAsRead']);

    // Étapes de livraison
    Route::get('/mes-etapes', [EtapeLivraisonController::class, 'mesEtapes']);
    Route::patch('/etapes/{id}/statut', [EtapeLivraisonController::class, 'changerStatut']);
    Route::patch('/etapes/{id}/cloturer', [EtapeLivraisonController::class, 'cloturerEtape']);

    // Livreur
    Route::get('/livreurs', [UtilisateurController::class, 'indexLivreurs']);

    // Portefeuille
    Route::get('/portefeuille', [PortefeuilleController::class, 'show']);
    Route::post('/portefeuille/credit', [PortefeuilleController::class, 'credit']);
    Route::post('/portefeuille/debit', [PortefeuilleController::class, 'debit']);

    // Adresse-Livraison
    Route::post('/adresses-livraison', [AdresseLivraisonController::class, 'store']);

    // Prestations
    Route::get('/prestataires', [PrestataireController::class, 'index']);
    Route::get('/prestations', [PrestationController::class, 'index']);
    Route::get('/prestations/catalogue', [PrestationController::class, 'catalogue']);
    Route::get('/prestations/{id}', [PrestationController::class, 'show']);
    Route::put('/prestations/{id}', [PrestationController::class, 'update']);
    Route::delete('/prestations/{id}', [PrestationController::class, 'destroy']);
    Route::patch('/prestations/{id}/statut', [PrestationController::class, 'changerStatut']);
    Route::patch('/prestations/{id}/reserver', [PrestationController::class, 'reserver']);


    // PlanningPrestataire
    Route::get('/plannings', [PlanningPrestataireController::class, 'index']);
    Route::post('/plannings', [PlanningPrestataireController::class, 'store']);
    Route::delete('/plannings/{id}', [PlanningPrestataireController::class, 'destroy']);

    // InterventionPrestataire
    Route::get('/interventions', [InterventionController::class, 'index']);
    Route::post('/interventions', [InterventionController::class, 'store']);
    Route::put('/interventions/{id}', [InterventionController::class, 'update']);

    // FacturePrestataire
    Route::get('/factures-prestataire', [FacturePrestataireController::class, 'mesFactures']);
    Route::post('/factures-prestataire/{mois}', [FacturePrestataireController::class, 'genererFactureMensuelle']);

    // Demande de renvoi du lien de confirmation par email
    Route::post('/email/verification-notification', [\Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController::class, 'store'])
        ->name('verification.send');


    // Double Authentification
    Route::get('/user/2fa-status', [TwoFactorController::class, 'status']);
    Route::post('/user/2fa/enable', [TwoFactorController::class, 'enable']);
    Route::post('/user/2fa/disable', [TwoFactorController::class, 'disable']);

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);
});


