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
    Schema::create('chambres', function (Blueprint $table) {
        $table->id();
        $table->string('numero')->unique();        // ex: "A101"
        $table->enum('type', ['simple', 'double', 'triple']);
        $table->integer('etage')->default(0);
        $table->integer('capacite')->default(1);
        $table->enum('statut', ['libre', 'occupee'])->default('libre');
        $table->boolean('publiee')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
