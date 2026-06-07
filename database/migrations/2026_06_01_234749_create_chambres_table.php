<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->enum('type', ['individuelle', 'double']);
            $table->string('bloc');
            $table->integer('etage');
            $table->integer('capacite');
            $table->string('etudiante_1')->nullable();
            $table->string('etudiante_2')->nullable();
            $table->boolean('publiee')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};