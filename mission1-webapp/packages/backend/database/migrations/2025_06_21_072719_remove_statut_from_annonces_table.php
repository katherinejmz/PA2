<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('annonces', function (Blueprint $table) {
            $table->dropColumn('statut');
        });
    }

    public function down()
    {
        Schema::table('annonces', function (Blueprint $table) {
            $table->enum('statut', ['en_attente', 'acceptee', 'en_cours', 'livree'])->default('en_attente');
        });
    }
};
