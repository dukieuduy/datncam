<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        // $products = Product::query()
        // ->with(['lowestVariation']) // Định nghĩa mối quan hệ lowestVariation bên dưới
        // ->get();

        //Danh mục
        $categories = Category::all();

        //Sản phẩm
        $products = Product::query()
            ->select('products.id', 'products.name')
            ->addSelect([
                'lowest_price_variation' => ProductVariation::select('price')
                    ->whereColumn('product_variations.product_id', 'products.id')
                    ->orderBy('price', 'asc')
                    ->limit(1),
                'lowest_price_image' => ProductVariation::select('image')  // Lấy ảnh của biến thể có giá thấp nhất
                ->whereColumn('product_variations.product_id', 'products.id')
                    ->orderBy('price', 'asc')
                    ->limit(1),
            ])
            ->with(['variations' => function ($query) {
                $query->orderBy('price', 'asc');
            }])
            ->get();
        // dd($products);
        return view('client.pages.home',compact('products','categories'));
    }

    public function detailProduct($id)
    {
        // Lấy sản phẩm cùng với các quan hệ: biến thể, danh mục, thuộc tính (màu sắc, kích thước)
        // $product = Product::with([
        //     'variations.variationAttributes.attributeValue.attribute', // Lấy thuộc tính của biến thể, bao gồm màu sắc, kích thước
        //     'category',
        //     'attributes' // Nếu có bảng pivot cho thuộc tính, tải các thuộc tính của sản phẩm
        // ])->findOrFail($id);
        $product = Product::findOrFail($id);


        // Tính tổng số lượng tồn kho từ các biến thể
        $variations = $product->variations;
        $category = $product->category;
        $stockQuantity = 0;
        foreach ($variations as $variation) {
            $stockQuantity += (int)$variation->stock_quantity;
        }

        // Lọc các biến thể có số lượng tồn kho > 0
        $availableVariations = $variations->filter(function ($variation) {
            return $variation->stock_quantity > 0;
        });

<<<<<<< HEAD
        // Lấy các giá trị màu sắc từ các biến thể có số lượng > 0
        $colorAttributes = $availableVariations->flatMap(function ($variation) {
            return $variation->variationAttributes->filter(function ($attribute) {
                return $attribute->attributeValue->attribute->name == 'Màu sắc'; // Giả sử tên thuộc tính là "Màu sắc"
            });
        });

        // Lấy các giá trị kích thước từ các biến thể có số lượng > 0
        $sizeAttributes = $availableVariations->flatMap(function ($variation) {
            return $variation->variationAttributes->filter(function ($attribute) {
                return $attribute->attributeValue->attribute->name == 'Kích thước'; // Giả sử tên thuộc tính là "Kích thước"
            });
        });

        // Lấy tất cả giá trị màu sắc và kích thước, loại bỏ các giá trị trùng lặp
        $colors = $colorAttributes->map(function ($attribute) {
            return $attribute->attributeValue->value; // Giá trị của màu sắc
        })->unique();

        $sizes = $sizeAttributes->map(function ($attribute) {
            return $attribute->attributeValue->value; // Giá trị của kích thước
        })->unique()->values(); // Loại bỏ trùng lặp và đánh chỉ số lại

        // Trả về view với dữ liệu
        return view('client.pages.detail', compact('product', 'variations', 'category', 'stockQuantity', 'colors', 'sizes'));
    }



=======
>>>>>>> 8c1884c419cde8324e16dfa787f728e295f7bcd7
}