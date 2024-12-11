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
            'title' => 'required|string|max:255|unique:promotions,title,',
            'type' => 'required|in:product,category,all_products,variant',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là một chuỗi văn bản.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'title.unique' => 'Tiêu đề đã tồn tại, vui lòng chọn tiêu đề khác.',

            'type.required' => 'Loại khuyến mãi là bắt buộc.',
            'type.in' => 'Loại khuyến mãi phải là một trong các giá trị: sản phẩm, danh mục, tất cả sản phẩm, biến thể sản phẩm.',

            'discount_percentage.required' => 'Phần trăm giảm giá là bắt buộc.',
            'discount_percentage.numeric' => 'Phần trăm giảm giá phải là một số.',
            'discount_percentage.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 1.',
            'discount_percentage.max' => 'Phần trăm giảm giá không được vượt quá 100.',

            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',

            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);


        // Tạo khuyến mãi mới
        $promotion = Promotion::create($request->all());

        // Lưu các thông tin liên quan (Sản phẩm, Danh mục, Biến thể)
        if ($request->type == 'product') {
            $promotion->products()->sync($request->products);
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
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'title.required' => 'Tiêu đề là bắt buộc.',
            'title.string' => 'Tiêu đề phải là một chuỗi văn bản.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'title.unique' => 'Tiêu đề đã tồn tại, vui lòng chọn tiêu đề khác.',

            'type.required' => 'Loại khuyến mãi là bắt buộc.',
            'type.in' => 'Loại khuyến mãi phải là một trong các giá trị: sản phẩm, danh mục, tất cả sản phẩm, biến thể sản phẩm.',

            'discount_percentage.required' => 'Phần trăm giảm giá là bắt buộc.',
            'discount_percentage.numeric' => 'Phần trăm giảm giá phải là một số.',
            'discount_percentage.min' => 'Phần trăm giảm giá phải lớn hơn hoặc bằng 1.',
            'discount_percentage.max' => 'Phần trăm giảm giá không được vượt quá 100.',

            'start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',

            'end_date.required' => 'Ngày kết thúc là bắt buộc.',
            'end_date.date' => 'Ngày kết thúc không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu.',
        ]);


        // Cập nhật chương trình khuyến mãi
        $promotion->update($request->all());

        // Lưu các thông tin liên quan (Sản phẩm, Danh mục, Biến thể)
        if ($request->type == 'product') {
            $promotion->products()->sync($request->products);
        } elseif ($request->type == 'category') {
            $promotion->categories()->sync($request->categories);
        }

        // Cập nhật trạng thái khuyến mãi (Nếu có)
        $promotion->status = $request->status;
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
