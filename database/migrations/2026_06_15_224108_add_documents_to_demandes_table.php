<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandes_renouvellements', function (Blueprint $table) {
            $table->string('decision_pdf')->nullable();
            $table->string('prise_en_charge_pdf')->nullable();
            $table->boolean('decision_remise')->default(false);
            $table->boolean('prise_en_charge_remise')->default(false);
            $table->timestamp('date_remise')->nullable();
        });

        Schema::table('demandes_changements', function (Blueprint $table) {
            $table->string('decision_pdf')->nullable();
            $table->string('prise_en_charge_pdf')->nullable();
            $table->boolean('decision_remise')->default(false);
            $table->boolean('prise_en_charge_remise')->default(false);
            $table->timestamp('date_remise')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('demandes_renouvellements', function (Blueprint $table) {
            $table->dropColumn([
                'decision_pdf', 'prise_en_charge_pdf',
                'decision_remise', 'prise_en_charge_remise', 'date_remise',
            ]);
        });

        Schema::table('demandes_changements', function (Blueprint $table) {
            $table->dropColumn([
                'decision_pdf', 'prise_en_charge_pdf',
                'decision_remise', 'prise_en_charge_remise', 'date_remise',
            ]);
        });
    }
};