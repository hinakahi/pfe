<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('maintenances', function (Blueprint $table) {
        $table->foreignId('chambre_id')->nullable()->change();
        $table->string('lieu_commun')->nullable()->after('chambre_id');
    });
}

   public function down(): void
{
    Schema::table('maintenances', function (Blueprint $table) {
        $table->dropColumn('lieu_commun');
        $table->foreignId('chambre_id')->nullable(false)->change();
    });
}
};
