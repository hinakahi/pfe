<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
 {
    Schema::table('materiels', function (Blueprint $table) {
        $table->foreignId('stock_id')->nullable()->after('maintenance_id')->constrained('stocks')->nullOnDelete();
        $table->dropColumn('nom_materiel'); // si tu supprimes bien ce champ
    });
 }

 public function down(): void
 {
    Schema::table('materiels', function (Blueprint $table) {
        $table->dropForeign(['stock_id']);
        $table->dropColumn('stock_id');
        $table->string('nom_materiel')->nullable();
    });
 }
};
