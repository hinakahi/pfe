<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vider les données texte existantes dans etudiante_1 et etudiante_2
        DB::table('chambres')->update([
            'etudiante_1' => null,
            'etudiante_2' => null,
        ]);
    }

    public function down(): void
    {
        //
    }
};