<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
{
    $product_id = $request->input('product_id');
    $quantity = $request->input('quantity', 1);

    // Kiểm tra nếu người dùng đã đăng nhập
    if (auth()->check()) {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
    } else {
        // Đối với người dùng khách, sử dụng session
        $cart_id = Session::get('cart_id');

        if (!$cart_id) {
            // Tạo mới giỏ hàng và lưu vào session
            $cart = Cart::create();
            Session::put('cart_id', $cart->id);
        } else {
            // Tìm giỏ hàng theo cart_id trong session
            $cart = Cart::find($cart_id);
        }
    }

    // Nếu cart không tồn tại (trường hợp hiếm), tạo một cart mới
    if (!$cart) {
        $cart = Cart::create();
        Session::put('cart_id', $cart->id);
    }

    // Thêm hoặc cập nhật sản phẩm trong giỏ hàng
    $cartItem = CartItem::updateOrCreate(
        ['cart_id' => $cart->id, 'product_id' => $product_id],
        ['quantity' => DB::raw("quantity + $quantity")]
    );

    return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng thành công']);
}

    // Hiển thị giỏ hàng
    public function showCart()
    {
        $cart = null;

        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();
        } else {
            $cart_id = Session::get('cart_id');
            if ($cart_id) {
                $cart = Cart::where('id', $cart_id)->with('items.product')->first();
            }
        }

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty']);
        }

        $totalQuantity = $cart->items->sum('quantity');
        return view('client.cart.show', compact('cart', 'totalQuantity'));
    }


    // Sửa số lượng sản phẩm trong giỏ hàng
    public function updateItem(Request $request, $productId)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = null;

        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->first();
        } else {
            $cart_id = Session::get('cart_id');
            if ($cart_id) {
                $cart = Cart::find($cart_id);
            }
        }

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        // Cập nhật số lượng sản phẩm
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
        if ($cartItem) {
            $cartItem->quantity = $validated['quantity'];
            $cartItem->save();
            return response()->json(['message' => 'Đã cập nhật thành công mục giỏ hàng']);
        }

        return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng'], 404);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function removeItem($productId)
    {
        $cart = null;

        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->first();
        } else {
            $cart_id = Session::get('cart_id');
            if ($cart_id) {
                $cart = Cart::find($cart_id);
            }
        }

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 404);
        }

        // Xóa sản phẩm khỏi giỏ hàng
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('message', 'Đã xóa thành công mục giỏ hàng');
        }

        return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng'], 404);
    }

    //checkout


    public function index()
    {
        return view('client.cart.hihi');
    }
}
