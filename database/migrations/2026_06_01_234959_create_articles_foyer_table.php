<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles_foyer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resp_foyer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nom_article');
            $table->text('description')->nullable();
            $table->decimal('prix', 8, 2);
            $table->integer('stock')->default(0);
            $table->string('photo')->nullable();
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles_foyer');
    }
};