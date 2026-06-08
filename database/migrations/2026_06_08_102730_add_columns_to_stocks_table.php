<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('stocks', function (Blueprint $table) {
        $table->id();
        $table->string('designation');
        $table->string('categorie')->nullable();
        $table->integer('quantite')->default(0);
        $table->string('unite')->default('unité');
        $table->integer('seuil_minimum')->default(5);
        $table->text('description')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::table('stocks', function (Blueprint $table) {
        $table->dropColumn(['designation', 'categorie', 'unite', 'seuil_minimum', 'description']);
    });
}
    /**
     * Reverse the migrations.
     */
    
};
