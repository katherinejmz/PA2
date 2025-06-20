<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etapes_livraison', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annonce_id')->constrained('annonces')->onDelete('cascade');
            $table->foreignId('livreur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('lieu_depart');
            $table->string('lieu_arrivee');
            $table->enum('statut', ['en_attente', 'en_cours', 'terminee'])->default('en_attente');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapes_livraison');
    }
};
