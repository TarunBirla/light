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
        Schema::table('auction_products', function (Blueprint $table) {
            $table->string('shipping_type')->default('excluded')->after('minprice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auction_products', function (Blueprint $table) {
            $table->dropColumn('shipping_type');
        });
    }
};
