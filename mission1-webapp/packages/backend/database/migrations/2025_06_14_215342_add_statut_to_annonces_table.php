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
        Schema::table('annonces', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'acceptee', 'en_cours', 'livree'])->default('en_attente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            //
        });
    }
};
