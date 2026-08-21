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
        if (!Schema::hasTable('equipment_requests')) {
            Schema::create('equipment_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('gaffer')->nullable();
                $table->string('email')->nullable();
                $table->string('contact')->nullable();
                $table->string('production_company')->nullable();
                $table->string('production_title')->nullable();
                $table->string('production_contact')->nullable();
                $table->string('dop')->nullable();
                
                // Dates
                $table->date('rig_from')->nullable();
                $table->date('rig_to')->nullable();
                $table->date('prelight_from')->nullable();
                $table->date('prelight_to')->nullable();
                $table->date('shoot_from')->nullable();
                $table->date('shoot_to')->nullable();
                $table->date('derig_from')->nullable();
                $table->date('derig_to')->nullable();
                
                // Location Address
                $table->string('address_line_1')->nullable();
                $table->string('address_line_2')->nullable();
                $table->string('address_line_3_postcode')->nullable();
                $table->text('location_address')->nullable();
                
                $table->string('status')->default('submitted');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('equipment_request_items')) {
            Schema::create('equipment_request_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('equipment_request_id')->constrained('equipment_requests')->onDelete('cascade');
                $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('items')->onDelete('cascade');
                $table->integer('quantity')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_request_items');
        Schema::dropIfExists('equipment_requests');
    }
};
