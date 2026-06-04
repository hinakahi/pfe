<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('matricule')->unique()->after('name');
            $table->enum('role', [
                'admin',
                'etudiante',
                'resp_hebergement',
                'technicien',
                'resp_foyer'
            ])->default('etudiante')->after('matricule');
            $table->string('phone')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['matricule', 'role', 'phone']);
        });
    }
};