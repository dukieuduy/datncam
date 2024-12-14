<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price_old','price_new', 
        'category_id', 'is_active'
    ];

        // Mối quan hệ với ProductVariation (một sản phẩm có nhiều biến thể)
        public function variations()
        {
            return $this->hasMany(ProductVariation::class);
        }
        public function category()
    {
        return $this->belongsTo(Category::class);
    }
        public function lowestVariation()
        {
            return $this->hasOne(ProductVariation::class)
                ->select('id', 'product_id', 'price', 'image')
                ->orderBy('price', 'asc'); // Lấy biến thể có giá thấp nhất
        }
}
