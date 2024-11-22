<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = ['user_id'];

    // public function items()
    // {
    //     return $this->hasMany(CartItem::class);
    // }
        public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function totalAmount()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

}
