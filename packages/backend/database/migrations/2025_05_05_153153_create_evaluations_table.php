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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('utilisateur_id'); // évalué (livreur ou prestataire)
            $table->unsignedBigInteger('client_id');      // évalueur
            $table->unsignedBigInteger('annonce_id');     // l'annonce concernée
        
            $table->tinyInteger('note')->comment('de 1 à 5');
            $table->text('commentaire')->nullable();
        
            $table->timestamps();
        
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('annonce_id')->references('id')->on('annonces')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
