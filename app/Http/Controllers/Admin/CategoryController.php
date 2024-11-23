<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        // Tìm kiếm theo tên danh mục
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sắp xếp theo tên
        if ($request->has('sort_by') && in_array($request->sort_by, ['asc', 'desc'])) {
            $query->orderBy('name', $request->sort_by);
        }

        // Phân trang kết quả
        $categories = $query->paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.category.create', compact('categories'));
    }

    public function store(Request $request)
    {
    
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' =>'required',
        ]);

        Category::create([
            'name' => $request->name,
            'is_active' =>$request->is_active,
        ]);

        
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->route('admin.categories.index')->with('error', 'Category not found');
        }
        $categories = Category::all(); 
        return view('admin.category.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'is_active' =>'required',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        // Trả về danh sách danh mục sau khi cập nhật
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        $catefories = Category::all();
        foreach($catefories as $x ){
            if($x->parent_id==$id){
                $x->delete();
            }
        }
        // Trả về danh sách danh mục sau khi xóa
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

}