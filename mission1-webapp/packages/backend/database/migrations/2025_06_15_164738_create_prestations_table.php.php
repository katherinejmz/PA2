<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('prestations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestataire_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('type_prestation');
            $table->text('description')->nullable();
            $table->timestamp('date_heure');
            $table->integer('duree_estimee')->nullable();
            $table->decimal('tarif', 10, 2);
            $table->string('statut')->default('en_attente'); // en_attente, acceptée, refusée, terminée
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('prestations');
    }
};

