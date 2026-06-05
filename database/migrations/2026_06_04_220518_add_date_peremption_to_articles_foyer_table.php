<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles_foyer', function (Blueprint $table) {
            $table->date('date_peremption')->nullable()->after('stock');
            $table->boolean('promo_active')->default(false)->after('date_peremption');
            $table->decimal('prix_promo', 8, 2)->nullable()->after('promo_active');
            $table->string('promo_remarque')->nullable()->after('prix_promo');
            $table->date('promo_date_fin')->nullable()->after('promo_remarque');
        });
    }

    public function down(): void
    {
        Schema::table('articles_foyer', function (Blueprint $table) {
            $table->dropColumn([
                'date_peremption',
                'promo_active',
                'prix_promo',
                'promo_remarque',
                'promo_date_fin',
            ]);
        });
    }
};