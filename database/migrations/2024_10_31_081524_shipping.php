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
        Schema::create('shipping', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('tracking_number')->nullable();
            $table->enum('shipping_method', ['standard', 'express']);
            $table->decimal('shipping_cost', 10, 2);
            $table->dateTime('shipped_date')->nullable();
            $table->dateTime('delivered_date')->nullable();
            $table->enum('status', ['pending', 'in_transit', 'delivered', 'returned']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping');
    }
};
