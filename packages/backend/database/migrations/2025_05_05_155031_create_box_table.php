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
        Schema::create('box', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entrepot_id');
            $table->string('code_box'); // identifiant interne
        
            $table->boolean('est_occupe')->default(false); // true si un colis est présent
        
            $table->timestamps();
        
            $table->foreign('entrepot_id')->references('id')->on('entrepots')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box');
    }
};
