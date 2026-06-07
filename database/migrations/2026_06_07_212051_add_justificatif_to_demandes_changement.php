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
    Schema::table('demandes_changement', function (Blueprint $table) {
        $table->string('justificatif')->nullable()->after('motif_refus');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes_changement', function (Blueprint $table) {
            //
        });
    }
};
