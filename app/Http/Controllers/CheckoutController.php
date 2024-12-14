<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class CheckoutController extends Controller
{
    // Hiển thị trang thanh toán
    public function checkout(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();

            $order = Order::create([
                'user_id' => Auth::user()->id,
                'order_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'total_amount' => $data['total_amount'],
                'status' => 'pending',
                'shipping_address' => $data['shipping_address'],
                'billing_address' => $data['shipping_address'],
            ]);
            foreach ($data['products'] as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'subtotal' => $product['subtotal'],
                ]);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function confirmCheckout(Request $request) {
        $cartItemIds = Arr::flatten($request->all());
        if(empty($cartItemIds) || empty($request->cart_item_id)) {
            return redirect()->back()->with('message', 'Chưa chọn sản phẩm nào để thanh toán!');
        }

        $cartItems = CartItem::whereIn('id', $cartItemIds)->get();

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        return view('client.pages.confirm_checkout', compact('cartItems', 'totalAmount'));
    }
}
