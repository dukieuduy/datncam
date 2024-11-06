<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(), 
            'description' => "Chúng ta của hiện tại",
            'price' => fake()->randomFloat(2, 1, 1000),
            'stock_quantity' => fake()->numberBetween(1, 100),
            'category_id' => fake()->numberBetween(1, 10),
            'image_url' => fake()->imageUrl(),
            'sku' => fake()->unique()->bothify('???-#####'),
            'is_active' => fake()->boolean(),
            'vendor_id' => fake()->numberBetween(1, 5),
        ];
    }
}