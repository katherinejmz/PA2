<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('annonce_utilisateur', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('annonce_id');
            $table->unsignedBigInteger('utilisateur_id'); // livreur
            $table->timestamp('accepte_le')->useCurrent();

            $table->foreign('annonce_id')->references('id')->on('annonces')->onDelete('cascade');
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');

            $table->unique(['annonce_id', 'utilisateur_id']); // évite les doublons
        });
    }

    public function down(): void {
        Schema::dropIfExists('annonce_utilisateur');
    }
};
