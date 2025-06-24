<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('etapes_livraison', function (Blueprint $table) {
            $table->dropColumn('est_mini_etape');
        });
    }

    public function down(): void
    {
        Schema::table('etapes_livraison', function (Blueprint $table) {
            $table->boolean('est_mini_etape')->default(false);
        });
    }
};
