<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Mối quan hệ với ProductAttributeValue (một thuộc tính có nhiều giá trị)
    public function values()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }
}
