<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
        public function index()
        {
            $products = Product::query()
                ->select('products.id', 'products.name', 'products.category_id')
                ->addSelect([
                    'lowest_price_variation' => ProductVariation::select('price')
                        ->whereColumn('product_variations.product_id', 'products.id')
                        ->orderBy('price', 'asc')
                        ->limit(1),
                    'lowest_price_image' => ProductVariation::select('image')
                        ->whereColumn('product_variations.product_id', 'products.id')
                        ->orderBy('price', 'asc')
                        ->limit(1),
                ])
                ->with(['variations' => function ($query) {
                    $query->orderBy('price', 'asc');
                }, 'category']) // Lấy thông tin danh mục
                ->get()
                ->map(function ($product) {
                    // Lưu giá gốc của sản phẩm
                    $product->original_price = $product->lowest_price_variation;

                    // Lấy khuyến mại áp dụng cho sản phẩm
                    $promotion = Promotion::where(function ($query) use ($product) {
                        $query->where('type', 'all_products') // Khuyến mãi toàn bộ sản phẩm
                            ->orWhere(function ($subQuery) use ($product) {
                                // Khuyến mãi theo danh mục
                                $subQuery->where('type', 'category')
                                    ->whereHas('categories', function ($catQuery) use ($product) {
                                        $catQuery->where('categories.id', $product->category_id);
                                    });
                            })
                            ->orWhere(function ($subQuery) use ($product) {
                                // Khuyến mãi cho sản phẩm cụ thể
                                $subQuery->where('type', 'product')
                                    ->whereHas('products', function ($prodQuery) use ($product) {
                                        $prodQuery->where('products.id', $product->id);
                                    });
                            });
                    })
                        ->where('status', true)
                        ->where('start_date', '<=', now())
                        ->where('end_date', '>=', now())
                        ->first();

                    // Áp dụng giảm giá nếu có khuyến mại
                    if ($promotion) {
                        $product->lowest_price_variation *= (1 - $promotion->discount_percentage / 100);
                        $product->promotion = $promotion; // Lưu thông tin khuyến mại vào sản phẩm
                    }

                    return $product;
                });

            return view('client.pages.home', compact('products'));
        }





    public function detailProduct($id)
    {
        // Lấy sản phẩm và các quan hệ
        $product = Product::findOrFail($id);
        $variations = $product->variations;
        $category = $product->category;

        // Tính tổng tồn kho
        $stockQuantity = $variations->sum('stock_quantity');

        // Lấy danh sách màu và kích thước có tồn kho
        $colors = $variations->pluck('variationAttributes')
            ->flatten()
            ->filter(fn($attr) => $attr->attributeValue->attribute->name == 'Màu sắc')
            ->pluck('attributeValue.value')
            ->unique();

        $sizes = $variations->pluck('variationAttributes')
            ->flatten()
            ->filter(fn($attr) => $attr->attributeValue->attribute->name == 'Kích thước')
            ->pluck('attributeValue.value')
            ->unique();

        // Lấy khuyến mãi
        $promotion = Promotion::where(function ($query) use ($product) {
            $query->where('type', 'all_products')
                ->orWhere(function ($subQuery) use ($product) {
                    $subQuery->where('type', 'category')
                        ->whereHas('categories', fn($q) => $q->where('categories.id', $product->category_id));
                })
                ->orWhere(function ($subQuery) use ($product) {
                    $subQuery->where('type', 'product')
                        ->whereHas('products', fn($q) => $q->where('products.id', $product->id));
                });
        })
            ->where('status', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        // Tính giá giảm giá
        $discountedPrice = null;
        $originalPrice = $variations->min('price'); // Giá thấp nhất của sản phẩm
        if ($promotion && $originalPrice) {
            $discountedPrice = $originalPrice * (1 - $promotion->discount_percentage / 100);
        }

        // Trả về view
        return view('client.pages.detail', compact(
            'product',
            'variations',
            'category',
            'stockQuantity',
            'colors',
            'sizes',
            'discountedPrice'
        ));
    }
}
