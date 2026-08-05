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
                if (!Schema::hasColumn('items', 'is_sell')) {
                    $table->boolean('is_sell')->default(true)->after('price_per_day');
                }
                if (!Schema::hasColumn('items', 'is_rental')) {
                    $table->boolean('is_rental')->default(true)->after('is_sell');
                }
            });
        }

        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                if (!Schema::hasColumn('requests', 'product_type')) {
                    $table->string('product_type')->default('rental')->after('item_name');
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
                if (Schema::hasColumn('items', 'is_sell')) {
                    $table->dropColumn('is_sell');
                }
                if (Schema::hasColumn('items', 'is_rental')) {
                    $table->dropColumn('is_rental');
                }
            });
        }

        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                if (Schema::hasColumn('requests', 'product_type')) {
                    $table->dropColumn('product_type');
                }
            });
        }
    }
};
