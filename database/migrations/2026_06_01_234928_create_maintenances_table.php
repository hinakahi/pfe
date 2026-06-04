<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('technicien_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('chambre_id')->constrained('chambres')->cascadeOnDelete();
            $table->string('description');
            $table->enum('type', ['electricite', 'plomberie', 'menuiserie', 'autre']);
            $table->enum('statut', ['en_attente', 'en_cours', 'terminee'])->default('en_attente');
            $table->enum('urgence', ['normale', 'urgente'])->default('normale');
            $table->timestamp('date_signalement')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};