<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationsTable extends Migration
{
    public function up()
    {
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Chưa có ràng buộc foreign key
            $table->string('sku')->unique(); // SKU riêng cho từng biến thể
            $table->decimal('price', 10, 2); // Giá của biến thể
            $table->integer('stock'); // Số lượng tồn kho
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variations');
    }
}
