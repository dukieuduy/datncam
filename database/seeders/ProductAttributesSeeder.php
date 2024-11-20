<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute; // Đảm bảo namespace này đúng

class ProductAttributesSeeder extends Seeder
{
    public function run()
    {
        // Thêm các thuộc tính vào bảng product_attributes
        ProductAttribute::create(['name' => 'Kích thước']);
        ProductAttribute::create(['name' => 'Màu sắc']);
    }
}
