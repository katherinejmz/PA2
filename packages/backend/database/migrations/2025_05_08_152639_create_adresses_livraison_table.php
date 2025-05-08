<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('adresses_livraison', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commande_id')->unique();
            $table->string('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->string('pays');
            $table->text('instructions')->nullable();
            $table->timestamps();

            $table->foreign('commande_id')->references('id')->on('commandes')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('adresses_livraison');
    }
};
