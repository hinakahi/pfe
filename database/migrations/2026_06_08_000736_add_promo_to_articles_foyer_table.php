<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('articles_foyer', function (Blueprint $table) {
        // 'categorie' retiré car existe déjà
        $table->boolean('promo_active')->default(false)->after('disponible');
        $table->decimal('prix_promo', 8, 2)->nullable()->after('promo_active');
        $table->date('promo_date_fin')->nullable()->after('prix_promo');
        $table->string('promo_remarque')->nullable()->after('promo_date_fin');
        $table->date('date_peremption')->nullable()->after('promo_remarque');
    });
}

public function down(): void
{
    Schema::table('articles_foyer', function (Blueprint $table) {
        $table->dropColumn([
            // 'categorie' retiré ici aussi
            'promo_active', 'prix_promo',
            'promo_date_fin', 'promo_remarque', 'date_peremption'
        ]);
    });
}
};
