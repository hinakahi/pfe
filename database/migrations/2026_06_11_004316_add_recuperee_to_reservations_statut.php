<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE reservations MODIFY COLUMN statut ENUM('panier','en_attente','validee','refusee','annulee','recuperee') NOT NULL DEFAULT 'panier'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE reservations MODIFY COLUMN statut ENUM('panier','en_attente','validee','refusee','annulee') NOT NULL DEFAULT 'panier'");
    }
};