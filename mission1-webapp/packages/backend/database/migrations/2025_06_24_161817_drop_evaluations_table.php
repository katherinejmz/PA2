<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('evaluations');
    }

    public function down(): void
    {
        Schema::create('evaluations', function ($table) {
            $table->id();
            $table->unsignedBigInteger('utilisateur_id');
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('annonce_id');
            $table->tinyInteger('note')->comment('de 1 à 5');
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('annonce_id')->references('id')->on('annonces')->onDelete('cascade');
        });
    }
};
