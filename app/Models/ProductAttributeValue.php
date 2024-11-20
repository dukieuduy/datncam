<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_attribute_id', 'value',
    ];

    // Mối quan hệ với ProductAttribute (giá trị thuộc về một thuộc tính)
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    // Mối quan hệ với ProductVariationAttribute (một giá trị thuộc tính có thể có nhiều biến thể sản phẩm)
    public function variationAttributes()
    {
        return $this->hasMany(ProductVariationAttribute::class);
    }
}

