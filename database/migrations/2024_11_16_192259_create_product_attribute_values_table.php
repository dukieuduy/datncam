<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributeValuesTable extends Migration
{
    public function up()
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_attribute_id'); // Chưa có ràng buộc foreign key
            $table->string('value'); // Giá trị (ví dụ: Đỏ, Xanh, S, M, L)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_attribute_values');
    }
}

