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
        Schema::create('portefeuilles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('utilisateur_id')->unique();
            $table->decimal('solde', 10, 2)->default(0);
            $table->timestamps();
        
            $table->foreign('utilisateur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portefeuilles');
    }
};
