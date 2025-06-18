<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('factures_prestataires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestataire_id')->constrained()->onDelete('cascade');
            $table->string('mois'); // Ex: "2025-06"
            $table->decimal('montant_total', 10, 2);
            $table->string('chemin_pdf');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('factures_prestataires');
    }
};
