<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColisTable extends Migration
{
    public function up(): void
    {
        Schema::create('colis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commande_id');
            $table->unsignedBigInteger('box_id')->nullable();
            $table->unsignedBigInteger('livreur_id')->nullable();
            $table->enum('etat', ['en_attente', 'en_depot', 'en_cours', 'livre'])->default('en_attente');
            $table->timestamp('date_depot')->nullable();
            $table->timestamp('date_retrait')->nullable();
            $table->timestamps();

            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
            $table->foreign('box_id')->references('id')->on('boxes')->onDelete('set null');
            $table->foreign('livreur_id')->references('id')->on('utilisateurs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colis');
    }
}

