<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariationAttributesTable extends Migration
{
    public function up()
    {
        Schema::create('product_variation_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_variation_id'); // Chưa có ràng buộc foreign key
            $table->unsignedBigInteger('product_attribute_id'); // Chưa có ràng buộc foreign key
            $table->unsignedBigInteger('product_attribute_value_id'); // Chưa có ràng buộc foreign key
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variation_attributes');
    }
}
