<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommercantController;
use App\Http\Controllers\PrestataireController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\LivraisonController;
use App\Http\Controllers\EtapeLivraisonController;
use App\Http\Controllers\LivreurEtapeController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\AuthController;


Route::get('/utilisateurs', [UtilisateurController::class, 'index']);
Route::post('/utilisateurs/store', [UtilisateurController::class, 'store']);
Route::get('/utilisateurs/{id}', [UtilisateurController::class, 'show']);
Route::patch('/utilisateurs/{id}', [UtilisateurController::class, 'update']);
Route::delete('/utilisateurs/{id}', [UtilisateurController::class, 'destroy']);

Route::get('/clients', [ClientController::class, 'index']);
Route::post('/clients/store', [ClientController::class, 'store']);
Route::get('/clients/{id}', [ClientController::class, 'show']);
Route::patch('/clients/{id}', [ClientController::class, 'update']);
Route::delete('/clients/{id}', [ClientController::class, 'destroy']);

Route::get('/commercants', [CommercantController::class, 'index']);
Route::post('/commercants/store', [CommercantController::class, 'store']);
Route::get('/commercants/{id}', [CommercantController::class, 'show']);
Route::patch('/commercants/{id}', [CommercantController::class, 'update']);
Route::delete('/commercants/{id}', [CommercantController::class, 'destroy']);

Route::get('/prestataires', [PrestataireController::class, 'index']);
Route::post('/prestataires/store', [PrestataireController::class, 'store']);
Route::get('/prestataires/{id}', [PrestataireController::class, 'show']);
Route::patch('/prestataires/{id}', [PrestataireController::class, 'update']);
Route::delete('/prestataires/{id}', [PrestataireController::class, 'destroy']);

Route::get('/livreurs', [LivreurController::class, 'index']);
Route::post('/livreurs/store', [LivreurController::class, 'store']);
Route::get('/livreurs/{id}', [LivreurController::class, 'show']);
Route::patch('/livreurs/{id}', [LivreurController::class, 'update']);
Route::delete('/livreurs/{id}', [LivreurController::class, 'destroy']);

Route::get('/annonces', [AnnonceController::class, 'index']);
Route::post('/annonces/store', [AnnonceController::class, 'store']);
Route::get('/annonces/{id}', [AnnonceController::class, 'show']);
Route::patch('/annonces/{id}', [AnnonceController::class, 'update']);
Route::delete('/annonces/{id}', [AnnonceController::class, 'destroy']);

Route::get('/livraisons', [LivraisonController::class, 'index']);
Route::post('/livraisons/store', [LivraisonController::class, 'store']);
Route::get('/livraisons/{id}', [LivraisonController::class, 'show']);
Route::patch('/livraisons/{id}', [LivraisonController::class, 'update']);
Route::delete('/livraisons/{id}', [LivraisonController::class, 'destroy']);

Route::get('/etapes', [EtapeLivraisonController::class, 'index']);
Route::post('/etapes/store', [EtapeLivraisonController::class, 'store']);
Route::get('/etapes/{id}', [EtapeLivraisonController::class, 'show']);
Route::patch('/etapes/{id}', [EtapeLivraisonController::class, 'update']);
Route::delete('/etapes/{id}', [EtapeLivraisonController::class, 'destroy']);

Route::get('/livreur-etapes', [LivreurEtapeController::class, 'index']);
Route::post('/livreur-etapes/store', [LivreurEtapeController::class, 'store']);
Route::get('/livreur-etapes/{id}', [LivreurEtapeController::class, 'show']);
Route::patch('/livreur-etapes/{id}', [LivreurEtapeController::class, 'update']);
Route::delete('/livreur-etapes/{id}', [LivreurEtapeController::class, 'destroy']);

Route::get('/communications', [CommunicationController::class, 'index']);
Route::post('/communications/store', [CommunicationController::class, 'store']);
Route::get('/communications/{id}', [CommunicationController::class, 'show']);
Route::patch('/communications/{id}', [CommunicationController::class, 'update']);
Route::delete('/communications/{id}', [CommunicationController::class, 'destroy']);

Route::get('/paiements', [PaiementController::class, 'index']);
Route::post('/paiements/store', [PaiementController::class, 'store']);
Route::get('/paiements/{id}', [PaiementController::class, 'show']);
Route::patch('/paiements/{id}', [PaiementController::class, 'update']);
Route::delete('/paiements/{id}', [PaiementController::class, 'destroy']);

Route::get('/factures', [FactureController::class, 'index']);
Route::post('/factures/store', [FactureController::class, 'store']);
Route::get('/factures/{id}', [FactureController::class, 'show']);
Route::patch('/factures/{id}', [FactureController::class, 'update']);
Route::delete('/factures/{id}', [FactureController::class, 'destroy']);

Route::get('/evaluations', [EvaluationController::class, 'index']);
Route::post('/evaluations/store', [EvaluationController::class, 'store']);
Route::get('/evaluations/{id}', [EvaluationController::class, 'show']);
Route::patch('/evaluations/{id}', [EvaluationController::class, 'update']);
Route::delete('/evaluations/{id}', [EvaluationController::class, 'destroy']);

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
