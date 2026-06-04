<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes_renouvellement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('chambre_id')->constrained('chambres')->cascadeOnDelete();
            $table->foreignId('resp_hebergement_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('statut', ['en_attente', 'validee', 'refusee'])->default('en_attente');
            $table->string('justificatif_scolarite')->nullable();
            $table->string('justificatif_paiement')->nullable();
            $table->text('motif_refus')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes_renouvellement');
    }
};