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

class ProductController extends Controller{
//     public function create()
// {
//     // $attributes = ProductAttribute::all();  // Lấy tất cả các thuộc tính
//     // return view('admin.products.create', compact('attributes'));
//     return view("admin.product.createProduct");


public function create()
{
    // Lấy tất cả các thuộc tính cùng giá trị liên quan
    $attributes = ProductAttribute::with('values')->get();

    // Lọc thuộc tính Color và Size
    $colors = $attributes->where('name', 'size')->first();
    $sizes = $attributes->where('name', 'color')->first();

    // Lấy danh sách giá trị của Color và Size
    $colorValues = $colors ? $colors->values : collect(); // Trả về mảng rỗng nếu không có
    $sizeValues = $sizes ? $sizes->values : collect();
    $category = Category::all();
    // Gửi dữ liệu sang view
    return view('admin.product.createProduct', compact('colorValues', 'sizeValues', 'category'));
}

public function store(Request $request)
{
    // Validate dữ liệu form
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'price_old' => 'nullable|numeric|min:0',
        'price_new' => 'nullable|numeric|min:0',
        'category' => 'required|exists:categories,id',
        'variations.*.price' => 'nullable|numeric|min:0',
        'variations.*.stock_quantity' => 'nullable|integer|min:0',
        'variations.*.attributes.*' => 'required|exists:product_attribute_values,id',
        'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'name.required' => "Tên sản phẩm không được để trống.",
        'description.required' => "Mô tả không được để trống.",
        'category.required' => "Danh mục sản phẩm là bắt buộc.",
        'category.exists' => "Danh mục không hợp lệ.",
        'variations.*.attributes.*.required' => "Thuộc tính của biến thể là bắt buộc.",
        'variations.*.image.image' => "File ảnh biến thể phải là định dạng ảnh.",
        'variations.*.image.max' => "Dung lượng file ảnh không được vượt quá 2MB.",
    ]);

    // Tạo sản phẩm
    $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'category_id' => $request->category,
        'price_old' => $request->price_old ?? 0,
        'price_new' => $request->price_new ?? 0,
    ]);

    // Lưu biến thể
    foreach ($request->variations as $index => $variation) {
        // Tạo SKU theo cú pháp id_sanpham-color-size
        $attributes = [];
        foreach ($variation['attributes'] as $attributeId => $valueId) {
            $attributeValue = ProductAttributeValue::find($valueId);
            if ($attributeValue) {
                $attributes[] = $attributeValue->value;
            }
        }

        // Tạo SKU
        $sku = $product->id . '-' . implode('-', $attributes);

        // Kiểm tra SKU có tồn tại hay chưa
        if (ProductVariation::where('sku', $sku)->exists()) {
            return redirect()->back()->withErrors([
                "variations.$index.attributes" => "Biến thể với SKU $sku đã tồn tại."
            ]);
        }

        // Lưu ảnh biến thể nếu có
        // $variationImagePath = null;
        // if (isset($variation['image']) && $variation['image'] instanceof \Illuminate\Http\UploadedFile) {
        //     // Lưu ảnh biến thể với tên duy nhất
        //     $variationImagePath = $variation['image']->storeAs('variations', $sku . '.' . $variation['image']->extension(), 'public');
        // }
        $variationImagePath = null;
        if (isset($variation['image']) && $variation['image'] instanceof \Illuminate\Http\UploadedFile) {
            $variationImagePath = $variation['image']->store('variations', 'public');
        }

        // Tạo biến thể
        $productVariation = ProductVariation::create([
            'product_id' => $product->id,
            'sku' => $sku,
            'price' => $variation['price'] ?? 0,
            'stock_quantity' => $variation['stock_quantity'] ?? 0,
            'image' => $variationImagePath,
        ]);

       // Lưu thuộc tính của biến thể
        foreach ($variation['attributes'] as $attributeName => $valueId) {
            // Tìm giá trị của thuộc tính (size, color, ...)
            $attributeValue = ProductAttributeValue::find($valueId);
            $attribute = ProductAttribute::where('name', $attributeName)->first(); // Tìm thuộc tính (size, color)
            if ($attributeValue && $attribute) {
                // Chèn thuộc tính biến thể vào bảng product_variation_attributes
                ProductVariationAttribute::create([
                    'product_variation_id' => $productVariation->id,
                    'product_attribute_value_id' => $valueId,  // Sử dụng valueId là ID của giá trị thuộc tính
                    'product_attribute_id' => $attribute->id,  // Sử dụng ID của thuộc tính (size, color)
                ]);
            } else {
                return redirect()->back()->withErrors([
                    "variations.$index.attributes" => "Giá trị thuộc tính không hợp lệ."
                ]);
            }
        }

    }

    return redirect()->back()->with('success', 'Sản phẩm đã được thêm thành công!');
}



// public function create()
// {
//     // Lấy tất cả các thuộc tính cùng giá trị liên quan
//     $attributes = ProductAttribute::with('values')->get();

//     // Lọc thuộc tính Color và Size
//     $colors = $attributes->where('name', 'Màu sắc')->first();
//     $sizes = $attributes->where('name', 'Kích thước')->first();

//     // Lấy danh sách giá trị của Color và Size
//     $colorValues = $colors ? $colors->values : collect(); // Trả về mảng rỗng nếu không có
//     $sizeValues = $sizes ? $sizes->values : collect();
//     $category = Category::all();

//     // Gửi dữ liệu sang view
//     return view('admin.product.createProduct', compact('colorValues', 'sizeValues', 'category'));
// }

// // }
// // public function create()
// //     {
// //         // Lấy danh sách các thuộc tính (kích thước, màu sắc)
// //         $attributes = ProductAttribute::with('values')->get();
// //         $category = Category::all();
// //         return view('admin.product.createProduct', compact('attributes','category'));
// //     }

//     // Xử lý lưu sản phẩm vào cơ sở dữ liệu

//     public function store(Request $request)
// {
//     // Validate dữ liệu form
//     $request->validate([
//         'name' => 'required',
//         'description' => 'required',
//         'price_old' => 'nullable|numeric|min:0',
//         'price_new' => 'nullable|numeric|min:0',
//         'category' => 'required|exists:categories,id',
//         'variations.*.price' => 'nullable|numeric|min:0',
//         'variations.*.stock_quantity' => 'nullable|integer|min:0',
//         'variations.*.attributes.*' => 'required|exists:product_attribute_values,id',
//     ], [
//         'name.required' => "Tên sản phẩm không được để trống.",
//         'description.required' => "Mô tả không được để trống.",
//         'category.required' => "Danh mục sản phẩm là bắt buộc.",
//         'category.exists' => "Danh mục không hợp lệ.",
//     ]);

//     // Tạo sản phẩm
//     $product = Product::create([
//         'name' => $request->name,
//         'description' => $request->description,
//         'category_id' => $request->category,
//     ]);

//     // Lưu biến thể
//     foreach ($request->variations as $variation) {
//         // Tạo SKU theo cú pháp id_sanpham-color-size
//         $attributes = [];
//         foreach ($variation['attributes'] as $attributeId => $valueId) {
//             $attributeValue = ProductAttributeValue::find($valueId);
//             $attributes[] = $attributeValue->value;
//         }

//         $sku = $product->id . '-' . implode('-', $attributes);

//         // Kiểm tra SKU có tồn tại hay chưa
//         if (ProductVariation::where('sku', $sku)->exists()) {
//             return redirect()->back()->withErrors(['variations' => "Biến thể với SKU $sku đã tồn tại."]);
//         }

//         // Lưu ảnh biến thể
//         $variationImagePath = null;
//         if (isset($variation['image']) && $variation['image'] instanceof \Illuminate\Http\UploadedFile) {
//             $variationImagePath = $variation['image']->store('variations', 'public');
//         }

//         // Tạo biến thể
//         $productVariation = ProductVariation::create([
//             'product_id' => $product->id,
//             'sku' => $sku,
//             'price' => $variation['price'],
//             'stock_quantity' => $variation['stock_quantity'],
//             'image' => $variationImagePath,
//         ]);

//         // Lưu thuộc tính của biến thể
//         foreach ($variation['attributes'] as $attributeId => $valueId) {
//             ProductVariationAttribute::create([
//                 'product_variation_id' => $productVariation->id,
//                 'product_attribute_value_id' => $valueId,
//                 'product_attribute_id' => $attributeId,
//             ]);
//         }
//     }

//     return redirect()->back()->with('success', 'Sản phẩm đã được thêm thành công!');
// }


    // public function store(Request $request)
    // {
    //     // dd($request->all());
    //     // Validation dữ liệu form
    //     $request->validate([
    //         'name' => 'required',
    //         'description' => 'required',
    //     ],[
    //         'name.required'=>"không được để trống tên sản phâm",
    //         'description.required'=>"không được để trống mô tả sản phâm",


    //     ]);


    //     // $imagePath = null;
    //     // if ($request->hasFile('image')) {
    //     //     $imagePath = $request->file('image')->store('products', 'public');
    //     // }

    //     // Thêm sản phẩm mới
    //     $product = Product::create([
    //         'name' => $request->name,
    //         'description' => $request->description,
    //         'category_id'=>$request->category,
    //     ]);

    //     // Lưu biến thể
    //     foreach ($request->variations as $variation) {
    //         $variationImagePath = null;
    //         if (isset($variation['image']) && is_file($variation['image'])) {
    //             $variationImagePath = $variation['image']->store('variations', 'public');
    //         }
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
    //                 // 'product_variation_id' => $productVariation->id,
    //                 'product_variation_id' => $productVariation->id,
    //                 'product_attribute_value_id' => $valueId,
    //                 'product_attribute_id' => $attributeId, // Thêm product_attribute_id
                    
    //             ]);
    //         }
    //     }

    //     return redirect()->back()->with('success', 'Sản phẩm đã được thêm thành công!');
    // }



}
