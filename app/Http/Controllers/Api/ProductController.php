<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::select('*')
                       ->orderBy('created_at', 'desc')
                       ->paginate(10);

    return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'category_id' => 'required|integer',
            'image_url' => 'nullable|url',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'is_active' => 'required|boolean',
            'vendor_id' => 'required|integer',
        ]);
        $product = Product::create($validatedData);
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if(!$product){
            return response()->json(['message'=>'không tìm thấy sản phẩm']);
        }
        return $product;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'stock_quantity' => 'sometimes|integer',
            'category_id' => 'sometimes|integer',
            'image_url' => 'nullable|url',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $id,
            'is_active' => 'sometimes|boolean',
            'vendor_id' => 'sometimes|integer',
        ]);

        $product->update($validatedData);

        return response()->json(['message' => 'Cập nhật sản phẩm thành công']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $product = Product::findOrFail($id);

        $product->delete();

        return response()->json(['message' => 'Xóa sản phẩm thành công']);
    }
}