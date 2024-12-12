<?php

namespace App\Http\Controllers\Admin;

use App\Models\Discount;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiscountController extends Controller
{
    // Hiển thị danh sách mã giảm giá
    public function index()
    {
        $discounts = Discount::all();
        return view('admin.discounts.index', compact('discounts'));
    }

    // Hiển thị form tạo mới mã giảm giá
    public function create()
    {
        $products = Product::all();
        $categories = Category::all();
        $users = User::all();
        return view('admin.discounts.create', compact('products', 'categories', 'users'));
    }

    // Lưu mã giảm giá mới
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discounts',
            'discount_type' => 'required',
            'value' => 'required|numeric',
            'min_purchase_amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $discount = Discount::create($request->all());

        // Liên kết với sản phẩm, danh mục, hoặc người dùng (nếu có)
        if ($request->discount_scope == 'product') {
            $discount->products()->sync($request->products);
        }

        if ($request->discount_scope == 'category') {
            $discount->categories()->sync($request->categories);
        }

        if ($request->discount_scope == 'user') {
            $discount->users()->sync($request->users);
        }

        return redirect()->route('admin.discounts.index')->with('success', 'Discount created successfully!');
    }

    public function show($id)
    {
        // Lấy mã giảm giá từ database theo ID
        $discount = Discount::findOrFail($id);

        // Trả về view chi tiết
        return view('admin.discounts.show', compact('discount'));
    }

    // Hiển thị form chỉnh sửa mã giảm giá
    public function edit($id)
    {
        $discount = Discount::findOrFail($id);
        $products = Product::all();
        $categories = Category::all();
        $users = User::all();

        return view('admin.discounts.edit', compact('discount', 'products', 'categories', 'users'));
    }

    // Cập nhật mã giảm giá
    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);

        // Validate input
        $request->validate([
            'code' => 'required|string|max:255',
            'discount_type' => 'required|string',
            'value' => 'required|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ]);

        // Update discount
        $discount->update([
            'code' => $request->input('code'),
            'discount_type' => $request->input('discount_type'),
            'value' => $request->input('value'),
            'max_discount_amount' => $request->input('max_discount_amount'),
            'min_purchase_amount' => $request->input('min_purchase_amount'),
            'usage_limit' => $request->input('usage_limit'),
            'usage_per_user' => $request->input('usage_per_user'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'discount_scope' => $request->input('discount_scope'),
            'is_active' => $request->input('is_active'), // Xử lý trường is_active
        ]);

        // Sync relationships if needed
        if ($request->input('discount_scope') === 'product') {
            $discount->products()->sync($request->input('products', []));
        } elseif ($request->input('discount_scope') === 'category') {
            $discount->categories()->sync($request->input('categories', []));
        } elseif ($request->input('discount_scope') === 'user') {
            $discount->users()->sync($request->input('users', []));
        }

        return redirect()->route('admin.discounts.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    // Xóa mã giảm giá
    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('admin.discounts.index')->with('success', 'Mã giảm giá đã được xóa thành công!');
    }
}
