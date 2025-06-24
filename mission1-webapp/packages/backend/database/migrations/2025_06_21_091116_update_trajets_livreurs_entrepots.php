<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTrajetsLivreursEntrepots extends Migration
{
    public function up(): void
    {
        Schema::table('trajets_livreurs', function (Blueprint $table) {
            $table->dropColumn(['ville_depart', 'ville_arrivee']);

            $table->unsignedBigInteger('entrepot_depart_id')->after('livreur_id');
            $table->unsignedBigInteger('entrepot_arrivee_id')->after('entrepot_depart_id');

            $table->foreign('entrepot_depart_id')->references('id')->on('entrepots')->onDelete('cascade');
            $table->foreign('entrepot_arrivee_id')->references('id')->on('entrepots')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('trajets_livreurs', function (Blueprint $table) {
            $table->dropForeign(['entrepot_depart_id']);
            $table->dropForeign(['entrepot_arrivee_id']);
            $table->dropColumn(['entrepot_depart_id', 'entrepot_arrivee_id']);

            $table->string('ville_depart')->nullable();
            $table->string('ville_arrivee')->nullable();
        });
    }
}
