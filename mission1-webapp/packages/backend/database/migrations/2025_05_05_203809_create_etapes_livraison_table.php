<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etapes_livraison', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('colis_id');
            $table->enum('statut', ['préparation', 'déposé', 'en transit', 'en attente', 'livré', 'échoué']);
            $table->text('commentaire')->nullable();
            $table->timestamp('date_etape')->useCurrent();

            $table->timestamps();

            $table->foreign('colis_id')->references('id')->on('colis')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etapes_livraison');
    }
};
