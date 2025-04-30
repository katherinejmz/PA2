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
            $table->unsignedBigInteger('id_client')->nullable();
            $table->unsignedBigInteger('id_livreur')->nullable();
            $table->unsignedBigInteger('id_prestataire')->nullable();
            $table->integer('note');
            $table->text('commentaire')->nullable();
            $table->timestamps();

            $table->foreign('id_client')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('set null');

            $table->foreign('id_livreur')
                  ->references('id')
                  ->on('livreurs')
                  ->onDelete('set null');

            $table->foreign('id_prestataire')
                  ->references('id')
                  ->on('prestataires')
                  ->onDelete('set null');
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
