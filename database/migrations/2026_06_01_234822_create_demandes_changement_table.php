<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes_changement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('chambre_actuelle_id')->constrained('chambres')->cascadeOnDelete();
            $table->foreignId('chambre_demandee_id')->nullable()->constrained('chambres')->nullOnDelete();
            $table->foreignId('resp_hebergement_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('motif');
            $table->enum('statut', ['en_attente', 'acceptee', 'refusee'])->default('en_attente');
            $table->text('motif_refus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes_changement');
    }
};