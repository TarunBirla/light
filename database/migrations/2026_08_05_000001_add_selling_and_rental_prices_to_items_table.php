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
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                if (!Schema::hasColumn('items', 'selling_price')) {
                    $table->decimal('selling_price', 10, 2)->nullable()->after('price_per_day');
                }
                if (!Schema::hasColumn('items', 'rental_price')) {
                    $table->decimal('rental_price', 10, 2)->nullable()->after('selling_price');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('items')) {
            Schema::table('items', function (Blueprint $table) {
                if (Schema::hasColumn('items', 'selling_price')) {
                    $table->dropColumn('selling_price');
                }
                if (Schema::hasColumn('items', 'rental_price')) {
                    $table->dropColumn('rental_price');
                }
            });
        }
    }
};
