<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderDiscount;
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
            OrderDiscount::create([
                'order_id' => $order->id,
                'discount_id' => $data['discount'],
                'discount_amount' => $data['discount_total'],
                'applied_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            $discount = Discount::findOrFail($data['discount']);
            $discount->quantity - 1;
            $discount->save();
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

        $discounts = Discount::where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->where('is_active', 1)->where('quantity', '>', 0)->get();
        $cartItems = CartItem::whereIn('id', $cartItemIds)->get();

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        return view('client.pages.confirm_checkout', compact('cartItems', 'totalAmount', 'discounts'));
    }
    public function getDataDiscount($id) {
        $discount = Discount::findOrFail($id);
        return response([
            'result' => true,
            'message' => "Success",
            'data' => $discount
        ], 200);
    }
}
