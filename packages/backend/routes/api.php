<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UtilisateurController;

Route::middleware('api')->group(function () {
    Route::apiResource('utilisateurs', UtilisateurController::class);
});
