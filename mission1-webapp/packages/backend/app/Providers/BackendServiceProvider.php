<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Charge les routes
        $this->loadRoutesFrom(base_path('routes/api.php'));
    }

    public function register()
    {
        // Vide pour l’instant (ne rien faire ici)
    }
}
