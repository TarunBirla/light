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
        Schema::create('auction_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_product_id')->constrained('auction_products')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->integer('qty')->default(1);
            $table->text('message')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auction_requests');
    }
};
