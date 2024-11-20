<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Đảm bảo import đúng model Category

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        // Thêm các danh mục sản phẩm
        Category::create(['name' => 'Áo thun']);
        Category::create(['name' => 'Quần jeans']);
        Category::create(['name' => 'Giày thể thao']);
        Category::create(['name' => 'Phụ kiện']);
    }
}
