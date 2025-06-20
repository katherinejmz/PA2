<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Entrepot;
use App\Models\Box;

class EntrepotsBoxesSeeder extends Seeder
{
    public function run()
    {
        $villes = [
            'Paris', 'Marseille', 'Lyon', 'Toulouse', 'Nice', 'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille',
            'Rennes', 'Reims', 'Le Havre', 'Cergy-Pontoise', 'Saint-Étienne', 'Toulon', 'Angers', 'Grenoble', 'Dijon', 'Nîmes'
        ];

        foreach ($villes as $ville) {
            $entrepot = Entrepot::create([
                'nom' => "Entrepôt de $ville",
                'adresse' => "$ville Centre Logistique",
                'ville' => $ville,
                'code_postal' => rand(10000, 99999)
            ]);

            for ($i = 1; $i <= 5; $i++) {
                Box::create([
                    'entrepot_id' => $entrepot->id,
                    'code_box' => "BOX-{$ville}-{$i}",
                    'est_occupe' => false
                ]);
            }
        }
    }
}
