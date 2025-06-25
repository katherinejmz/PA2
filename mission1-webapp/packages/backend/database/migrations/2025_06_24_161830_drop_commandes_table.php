<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Supprimer la contrainte étrangère sur paiements
        Schema::table('paiements', function ($table) {
            $table->dropForeign(['commande_id']);
        });

        // Ensuite supprimer commandes
        Schema::dropIfExists('commandes');
    }

    public function down(): void {
        Schema::create('commandes', function ($table) {
            $table->id();
            $table->unsignedBigInteger('annonce_id');
            $table->unsignedBigInteger('client_id');
            $table->decimal('montant', 8, 2);
            $table->enum('statut', ['en_attente', 'paye', 'annule'])->default('en_attente');
            $table->timestamp('achete_le')->useCurrent();
            $table->timestamps();

            $table->foreign('annonce_id')->references('id')->on('annonces')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });

        Schema::table('paiements', function ($table) {
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
        });
    }
};
