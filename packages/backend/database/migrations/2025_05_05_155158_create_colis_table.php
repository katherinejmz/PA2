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
        Schema::create('colis', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('annonce_id');
            $table->unsignedBigInteger('box_id')->nullable();
            $table->unsignedBigInteger('livreur_id')->nullable(); // celui qui l'a déposé ou pris en charge
        
            $table->enum('etat', ['en_attente', 'en_depot', 'en_cours', 'livre'])->default('en_attente');
            $table->timestamp('date_depot')->nullable();
            $table->timestamp('date_retrait')->nullable();
        
            $table->timestamps();
        
            $table->foreign('annonce_id')->references('id')->on('annonces')->onDelete('cascade');
            $table->foreign('box_id')->references('id')->on('box')->onDelete('set null');
            $table->foreign('livreur_id')->references('id')->on('utilisateurs')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colis');
    }
};
