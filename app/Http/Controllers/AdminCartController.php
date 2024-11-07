<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;

class AdminCartController extends Controller
{
    // Hiển thị danh sách giỏ hàng
    public function index()
    {
        $carts = Cart::with('items.product')->get();
        return view('admin.carts.index', compact('carts'));
    }

     // Hiển thị chi tiết giỏ hàng
     public function show($id)
     {
         $cart = Cart::with('items.product')->findOrFail($id); // Tìm giỏ hàng theo ID
         return view('admin.carts.show', compact('cart'));
     }

// Xóa giỏ hàng
public function destroy($id)
{
    $cart = Cart::find($id);
    if ($cart) {
        $cart->items()->delete(); // Xóa các sản phẩm trong giỏ hàng
        $cart->delete(); // Xóa giỏ hàng
        return redirect()->route('admin.carts.index')->with('message', 'Cart deleted successfully');
    }
    return redirect()->route('admin.carts.index')->with('error', 'Cart not found');
}

}

