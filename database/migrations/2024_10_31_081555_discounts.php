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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2); // Số tiền hoặc % giảm giá
            $table->decimal('max_discount_amount', 10, 2)->nullable(); // Số tiền giảm tối đa
            $table->decimal('min_purchase_amount', 10, 2)->default(0); // Số tiền tối thiểu để áp dụng
            $table->unsignedInteger('usage_limit')->nullable(); // Số lần sử dụng tối đa
            $table->unsignedInteger('usage_per_user')->nullable(); // Giới hạn cho mỗi user
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
