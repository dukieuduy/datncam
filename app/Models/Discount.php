<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'percentage',
        'start_date',
        'end_date',
        'min_purchase_amount',
        'quantity',
        'is_active',
    ];

}
