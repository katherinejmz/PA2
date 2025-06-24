<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            // Suppression des anciens champs texte
            $table->dropColumn(['lieu_depart', 'lieu_arrivee']);

            // Ajout des nouvelles clés étrangères
            $table->unsignedBigInteger('entrepot_depart_id')->nullable()->after('photo');
            $table->unsignedBigInteger('entrepot_arrivee_id')->nullable()->after('entrepot_depart_id');

            $table->foreign('entrepot_depart_id')->references('id')->on('entrepots')->onDelete('set null');
            $table->foreign('entrepot_arrivee_id')->references('id')->on('entrepots')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('annonces', function (Blueprint $table) {
            $table->dropForeign(['entrepot_depart_id']);
            $table->dropForeign(['entrepot_arrivee_id']);
            $table->dropColumn(['entrepot_depart_id', 'entrepot_arrivee_id']);

            $table->string('lieu_depart')->nullable();
            $table->string('lieu_arrivee')->nullable();
        });
    }
};
