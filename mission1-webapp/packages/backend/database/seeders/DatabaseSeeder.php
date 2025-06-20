<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // ➕ Ajout du seeder des entrepôts + boxes
        $this->call(EntrepotsBoxesSeeder::class);
    }
}
