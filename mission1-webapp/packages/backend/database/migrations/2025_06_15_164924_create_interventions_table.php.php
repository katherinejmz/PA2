<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestation_id')->constrained()->onDelete('cascade');
            $table->text('commentaire_client')->nullable();
            $table->tinyInteger('note')->nullable(); // note de 1 à 5
            $table->string('statut_final')->default('non effectuée'); // ou "effectuée"
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('interventions');
    }
};

