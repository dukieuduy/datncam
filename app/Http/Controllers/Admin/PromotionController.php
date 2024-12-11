<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all(); // Lấy tất cả các chương trình khuyến mãi
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $products = Product::all(); // Lấy danh sách sản phẩm
        $categories = Category::all(); // Lấy danh sách danh mục
        $variants = ProductVariation::all(); // Lấy danh sách biến thể sản phẩm

        return view('admin.promotions.create', compact('products', 'categories', 'variants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:promotions,title',
            'type' => 'required|in:product,category,all_products,variant',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Tạo khuyến mãi mới
        $promotion = Promotion::create($request->all());

        // Lưu các thông tin liên quan (Sản phẩm, Danh mục, Biến thể)
        if ($request->type == 'product') {
            $promotion->products()->sync($request->products);
            // Lấy mức giảm giá cao nhất trong các sản phẩm
            $highest_discount = Product::whereIn('id', $request->products)
                ->max('discount_percentage');
            $promotion->discount_percentage = $highest_discount;
        } elseif ($request->type == 'category') {
            $promotion->categories()->sync($request->categories);
        } elseif ($request->type == 'variant') {
            $promotion->variants()->sync($request->variants);
        }

        $promotion->save();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Thêm mã khuyến mãi thành công!');
    }

    public function edit($id)
    {
        $promotion = Promotion::findOrFail($id);
        $products = Product::all();
        $categories = Category::all();
        $variants = ProductVariation::all();

        return view('admin.promotions.edit', compact('promotion', 'products', 'categories', 'variants'));
    }

    public function update(Request $request, $id)
    {
        $promotion = Promotion::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255|unique:promotions,title,' . $promotion->id,
            'type' => 'required|in:product,category,all_products,variant',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Cập nhật chương trình khuyến mãi
        $promotion->update($request->all());

        // Lưu các thông tin liên quan (Sản phẩm, Danh mục, Biến thể)
        if ($request->type == 'product') {
            $promotion->products()->sync($request->products);
        } elseif ($request->type == 'category') {
            $promotion->categories()->sync($request->categories);
        } elseif ($request->type == 'variant') {
            $promotion->variants()->sync($request->variants);
        }

        // Cập nhật trạng thái khuyến mãi (Nếu có)
        $promotion->status = $request->status; // Giả sử bạn có trường `status` trong bảng promotion
        $promotion->save();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Sửa mã giảm giá thành công!');
    }


    public function destroy($id)
    {
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Xóa mã khuyến mãi thành công!');
    }
}
