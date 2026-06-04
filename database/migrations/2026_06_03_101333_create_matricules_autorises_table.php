<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matricules_autorises', function (Blueprint $table) {
            $table->id();
            $table->string('matricule', 191)->unique();
            $table->boolean('utilise')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matricules_autorises');
    }
};