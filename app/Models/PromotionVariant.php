<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionVariant extends Model
{
    use HasFactory;
    protected $fillable = ['promotion_id', 'product_variation_id'];
}
