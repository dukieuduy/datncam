<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use App\Models\ProductVariation;
use App\Models\ProductVariationAttribute;
use Illuminate\Support\Facades\Storage; 

class ProductController extends Controller
{
//     public function create()
// {
//     // $attributes = ProductAttribute::all();  // Lấy tất cả các thuộc tính
//     // return view('admin.products.create', compact('attributes'));
//     return view("admin.product.createProduct");
    
// }
public function create()
    {
        // Lấy danh sách các thuộc tính (kích thước, màu sắc)
        $attributes = ProductAttribute::with('values')->get();
        $category = Category::all();
        return view('admin.product.createProduct', compact('attributes','category'));
    }

    // Xử lý lưu sản phẩm vào cơ sở dữ liệu
    public function store(Request $request)
    {
        // dd($request->all());
        // Validation dữ liệu form
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ],[
            'name.required'=>"không được để trống tên sản phâm",
            'description.required'=>"không được để trống mô tả sản phâm",


        ]);


        // $imagePath = null;
        // if ($request->hasFile('image')) {
        //     $imagePath = $request->file('image')->store('products', 'public');
        // }

        // Thêm sản phẩm mới
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id'=>$request->category,
        ]);

        // Lưu biến thể
        foreach ($request->variations as $variation) {
            $variationImagePath = null;
            if (isset($variation['image']) && is_file($variation['image'])) {
                $variationImagePath = $variation['image']->store('variations', 'public');
            }
            $productVariation = ProductVariation::create([
                'product_id' => $product->id,
                'sku' => $variation['sku'],
                'price' => $variation['price'],
                'stock_quantity' => $variation['stock_quantity'],
                'image' => $variationImagePath, // Lưu đường dẫn ảnh biến thể
                
            ]);

            // Lưu thuộc tính cho biến thể
            foreach ($variation['attributes'] as $attributeId => $valueId) {
                ProductVariationAttribute::create([
                    // 'product_variation_id' => $productVariation->id,
                    'product_variation_id' => $productVariation->id,
                    'product_attribute_value_id' => $valueId,
                    'product_attribute_id' => $attributeId, // Thêm product_attribute_id
                    
                ]);
            }
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm thành công!');
    }


// public function store(Request $request)
// {
//     // Validation dữ liệu form
//     $request->validate([
//         'name' => 'required',
//         'description' => 'required',
//     ], [
//         'name.required' => "Không được để trống tên sản phẩm",
//         'description.required' => "Không được để trống mô tả sản phẩm",
//     ]);

//     // Thêm sản phẩm mới
//     $product = Product::create([
//         'name' => $request->name,
//         'description' => $request->description,
//         'category_id' => $request->category,
//     ]);

//     // Lưu biến thể
//     foreach ($request->variations as $variation) {
//         // Lưu ảnh cho biến thể (nếu có)
//         $variationImagePath = null;
//         if (isset($variation['image']) && $variation['image'] instanceof \Illuminate\Http\UploadedFile) {
//             // Lưu ảnh vào thư mục 'variations' trong ổ đĩa 'public'
//             $variationImagePath = $variation['image']->store('variations', 'public'); 
//             // Hoặc dùng Storage::url để tạo đường dẫn public từ storage
//             $variationImagePath = Storage::url($variationImagePath);  // Chuyển đường dẫn thành đường dẫn công khai
//         }

//         // Lưu biến thể sản phẩm
//         $productVariation = ProductVariation::create([
//             'product_id' => $product->id,
//             'sku' => $variation['sku'],
//             'price' => $variation['price'],
//             'stock_quantity' => $variation['stock_quantity'],
//             'image' => $variationImagePath, // Lưu đường dẫn ảnh biến thể
//         ]);

//         // Lưu thuộc tính cho biến thể
//         foreach ($variation['attributes'] as $attributeId => $valueId) {
//             ProductVariationAttribute::create([
//                 'product_variation_id' => $productVariation->id,
//                 'product_attribute_value_id' => $valueId,
//                 'product_attribute_id' => $attributeId, // Thêm product_attribute_id
//             ]);
//         }
//     }

//     return redirect()->back()->with('success', 'Sản phẩm đã được thêm thành công!');
// }

}
