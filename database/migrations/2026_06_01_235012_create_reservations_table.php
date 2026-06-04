<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('article_id')->constrained('articles_foyer')->cascadeOnDelete();
            $table->foreignId('resp_foyer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('quantite')->default(1);
            $table->enum('statut', ['en_attente', 'validee', 'refusee', 'annulee'])->default('en_attente');
            $table->timestamp('date_reservation')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};