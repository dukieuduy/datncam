<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'type',
        'discount_percentage',
        'start_date',
        'end_date',
        'status',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'promotion_category');
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariation::class, 'promotion_variant');
    }
}
