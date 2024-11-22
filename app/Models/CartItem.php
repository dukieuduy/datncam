<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['cart_id', 'product_id', 'product_name','color','size','price','image', 'quantity','product_variation_id'];

    // Mối quan hệ với sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }


}
