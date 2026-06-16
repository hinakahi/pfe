<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandes_renouvellement', function (Blueprint $table) {
            $table->json('materiel_json')->nullable();
        });

        Schema::table('demandes_changement', function (Blueprint $table) {
            $table->json('materiel_json')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('demandes_renouvellement', function (Blueprint $table) {
            $table->dropColumn('materiel_json');
        });

        Schema::table('demandes_changement', function (Blueprint $table) {
            $table->dropColumn('materiel_json');
        });
    }
};