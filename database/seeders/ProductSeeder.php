<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            Product::create([
                'name' => $faker->word,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 10, 100),
                'stock_quantity' => $faker->numberBetween(1, 100),
                'category_id' => $faker->numberBetween(1, 5), // Giả định bạn có ít nhất 5 danh mục
                'image_url' => $faker->imageUrl(),
                'sku' => $faker->unique()->word,
                'is_active' => $faker->boolean,
                'vendor_id' => $faker->numberBetween(1, 10), // Giả định bạn có ít nhất 10 nhà cung cấp
            ]);
        }
    }
}
