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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['product', 'category', 'all_products', 'variant']); // Loại khuyến mãi
            $table->decimal('discount_percentage', 5, 2); // Phần trăm giảm giá
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('status')->default(true); // Trạng thái khuyến mãi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
