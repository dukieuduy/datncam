<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttributeValue; // Đảm bảo đã import model đúng

class ProductAttributeValuesSeeder extends Seeder
{
    public function run()
    {
        // Lấy các thuộc tính đã tạo trước đó
        $size = \App\Models\ProductAttribute::where('name', 'Kích thước')->first();
        $color = \App\Models\ProductAttribute::where('name', 'Màu sắc')->first();

        // Thêm các giá trị cho thuộc tính "Kích thước"
        $sizeValues = ['S', 'M', 'L', 'XL'];
        foreach ($sizeValues as $value) {
            ProductAttributeValue::create([
                'product_attribute_id' => $size->id,
                'value' => $value
            ]);
        }

        // Thêm các giá trị cho thuộc tính "Màu sắc"
        $colorValues = ['Đỏ', 'Vàng', 'Xanh'];
        foreach ($colorValues as $value) {
            ProductAttributeValue::create([
                'product_attribute_id' => $color->id,
                'value' => $value
            ]);
        }
    }
}
