<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('articles_foyer', function (Blueprint $table) {
        $table->unsignedInteger('promo_qte_lot')->nullable()->after('promo_remarque');
        $table->decimal('promo_prix_lot', 8, 2)->nullable()->after('promo_qte_lot');
    });
}

public function down()
{
    Schema::table('articles_foyer', function (Blueprint $table) {
        $table->dropColumn(['promo_qte_lot', 'promo_prix_lot']);
    });
}
};
