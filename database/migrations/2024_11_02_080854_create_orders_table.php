<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->dateTime('order_date');
            $table->decimal('total_amount', 10, 2);
            $table->enum('status', ['pending', 'shipped', 'completed', 'canceled', 'refunded']);
            $table->string('shipping_address');
            $table->string('billing_address');
            $table->timestamps();
            $table->unsignedBigInteger('discount_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
