<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('communications');
    }

    public function down(): void
    {
        Schema::create('communications', function ($table) {
            $table->id();
            $table->unsignedBigInteger('expediteur_id');
            $table->unsignedBigInteger('destinataire_id');
            $table->unsignedBigInteger('annonce_id')->nullable();
            $table->text('message');
            $table->timestamp('lu_at')->nullable();
            $table->timestamps();

            $table->foreign('expediteur_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('destinataire_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('annonce_id')->references('id')->on('annonces')->onDelete('cascade');
        });
    }
};
