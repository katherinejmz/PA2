<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['livraison_client', 'produit_livre', 'service']);
            $table->string('titre');
            $table->text('description');
            $table->decimal('prix_propose', 8, 2);
            $table->string('photo')->nullable();

            // Références vers des utilisateurs selon leur rôle
            $table->unsignedBigInteger('id_client');
            $table->unsignedBigInteger('id_commercant')->nullable();
            $table->unsignedBigInteger('id_prestataire')->nullable();

            $table->string('lieu_depart')->nullable();
            $table->string('lieu_arrivee')->nullable();

            $table->timestamps();

            // Clés étrangères vers la table "utilisateurs"
            $table->foreign('id_client')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('id_commercant')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('id_prestataire')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};
