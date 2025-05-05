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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
        
            $table->unsignedBigInteger('utilisateur_id'); // celui qui paie ou reçoit
            $table->unsignedBigInteger('commande_id')->nullable(); // si rattaché à une commande
        
            $table->decimal('montant', 10, 2);
            $table->enum('sens', ['credit', 'debit']); // credit = reçoit, debit = paie
            $table->enum('type', ['stripe', 'portefeuille', 'virement', 'remboursement']);
            $table->string('reference')->nullable();
            $table->timestamps();
        
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
