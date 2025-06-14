<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Liste des exceptions non signalées.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [];

    /**
     * Liste des inputs jamais flashés pour la validation.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Gérer une exception non authentifiée (API).
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['message' => 'Non authentifié.'], 401);
    }

    /**
     * Enregistre les callbacks pour la gestion des exceptions.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
