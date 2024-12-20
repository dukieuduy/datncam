<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'is_active',
    ];


    public function products()
    {
        return $this->hasMany(Product::class);
    }
<<<<<<< HEAD

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_category');
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'discount_category', 'category_id', 'discount_id');
    }
}
=======
}
>>>>>>> 8c1884c419cde8324e16dfa787f728e295f7bcd7
