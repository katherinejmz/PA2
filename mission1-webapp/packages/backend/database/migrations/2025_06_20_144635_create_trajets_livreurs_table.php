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
        Schema::create('trajets_livreurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livreur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('ville_depart');
            $table->string('ville_arrivee');
            $table->date('disponible_du')->nullable();
            $table->date('disponible_au')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trajets_livreurs');
    }
};
