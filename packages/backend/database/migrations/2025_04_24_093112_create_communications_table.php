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
        Schema::create('communications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_etape');
            $table->unsignedBigInteger('id_livreur');
            $table->text('message');
            $table->timestamps();

            $table->foreign('id_etape')
                  ->references('id')
                  ->on('etapes_livraison')
                  ->onDelete('cascade');

            $table->foreign('id_livreur')
                  ->references('id')
                  ->on('livreurs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('communications');
    }
};
