<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiante_id')->constrained('users')->cascadeOnDelete();
            $table->string('sujet');
            $table->text('message');
            $table->enum('statut', ['en_attente', 'traitee', 'fermee'])->default('en_attente');
            $table->text('reponse')->nullable();
            $table->timestamp('date_reclamation')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};