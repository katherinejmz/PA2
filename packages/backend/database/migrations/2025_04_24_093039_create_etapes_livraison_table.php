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
            $table->unsignedBigInteger('id_livraison');
            $table->integer('ordre');
            $table->string('lieu_depart');
            $table->string('lieu_arrivee');
            $table->string('statut')->default('en attente');
            $table->dateTime('date_prise_en_charge')->nullable();
            $table->timestamps();

            $table->foreign('id_livraison')
                  ->references('id')
                  ->on('livraisons')
                  ->onDelete('cascade');
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
