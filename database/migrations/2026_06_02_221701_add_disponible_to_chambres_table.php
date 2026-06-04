<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chambres', function (Blueprint $table) {
            if (!Schema::hasColumn('chambres', 'disponible')) {
                $table->boolean('disponible')->default(true)->after('etage');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chambres', function (Blueprint $table) {
            $table->dropColumn('disponible');
        });
    }
};