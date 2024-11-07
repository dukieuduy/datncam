<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    // Hiển thị trang thanh toán
    public function checkout()
    {
        // Giả sử bạn muốn lấy thông tin giỏ hàng
        $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();

        if (!$cart) {
            return redirect()->route('carts.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        return view('user.checkout.checkout', compact('cart'));
    }

}
