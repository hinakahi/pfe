<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles_foyer', function (Blueprint $table) {
            $table->enum('categorie', [
                'fastfood',
                'magasin',
                'cafeteria'
            ])->after('nom_article');
        });
    }

    public function down(): void
    {
        Schema::table('articles_foyer', function (Blueprint $table) {
            $table->dropColumn('categorie');
        });
    }
};