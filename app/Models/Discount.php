<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'discount_type',
        'value',
        'max_discount_amount',
        'min_purchase_amount',
        'usage_limit',
        'usage_per_user',
        'discount_scope',
        'start_date',
        'end_date',
        'is_active'
    ];

    // Quan hệ với DiscountUser
    public function users()
    {
        return $this->belongsToMany(User::class, 'discount_user', 'discount_id', 'user_id');
    }

    // Quan hệ với DiscountProduct
    public function products()
    {
        return $this->belongsToMany(Product::class, 'discount_product', 'discount_id', 'product_id');
    }

    // Quan hệ với DiscountCategory
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'discount_category', 'discount_id', 'category_id');
    }
}
