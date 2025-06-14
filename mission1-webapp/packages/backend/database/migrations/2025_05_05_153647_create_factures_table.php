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
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('utilisateur_id'); // prestataire ou commerçant
            $table->decimal('montant_total', 10, 2);
            $table->string('chemin_pdf'); // lien vers le fichier PDF généré
            $table->timestamp('date_emission')->useCurrent();
        
            $table->timestamps();
        
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
