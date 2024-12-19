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
        $totalAll = number_format($totalAmount + 55000, 0, '.', '.');
        return view('client.pages.confirm_checkout', compact('cartItems', 'totalAmount', 'discounts', 'totalAll'));
    }
    public function getDataDiscount($id) {
        $discount = Discount::findOrFail($id);
        return response([
            'result' => true,
            'message' => "Success",
            'data' => $discount
        ], 200);
    }

    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function pay(Request $request)
    {

        // $request['cart_total'] = (float)$request->cart_total;

        // if ($request['payment'] == 1) {

        //     $vnpayRequest = [
        //         'cart_total' => (float)$request->cart_total,
        //         // 'ho_ten' => $ho_ten,
        //         // 'so_dien_thoai' => $so_dien_thoai,
        //         // 'email' => $email,

        //     ];
        //     return redirect()->route('vnpay_payment', [$request])->with($vnpayRequest);
        //     // return $this->checkoutSuccess1($request);
        // }
        // if ($request['payment'] == 2) {
        //     return redirect()->route('momo_payment', [$request]);
        // }


        $paymentMethod = $request->input('payment_method');
        $request['cart_total'] = (float)$request->cart_total;
        // dd($request->cart_total);

        if($paymentMethod == 'CASH'){
            // sửa lại thanh toán bằng tiền mặt
            return redirect()->route('checkout');

        }

        if ($paymentMethod === 'VNPAY') {
            // dd(1);
            $vnpayRequest = [
                'cart_total' => (float)$request->cart_total,
            ];
            return redirect()->route('vnpay_payment', [$request])->with($vnpayRequest);
        }

        if ($paymentMethod === 'MOMO') {
            return redirect()->route('momo_payment', [$request]);
        }
    }


    public function momo_payment(Request $request)
    {
        
        // dd(1);
        // Log::info('Request data:', $request->all());
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = 'Thanh toán đơn hàng qua MoMo';
        $amount = (float)$request['cart_total'] * 1000;
        // dd($amount);
        $orderId = time() . "";
        $redirectUrl = "http://127.0.0.1:8000";
        $ipnUrl = "http://127.0.0.1:8000";
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";

        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));
        // dd($result);

        $jsonResult = json_decode($result, true);  // decode json
        // dd($jsonResult);
        // redirect()->to($jsonResult['payUrl']);

        // if($jsonResult = json_decode($result, true)){
        //     // return view('admin.layouts.master');
        //     return redirect()->to($jsonResult['payUrl']);
        // }

        return redirect()->to($jsonResult['payUrl']);

        
    }

    public function momoCallback(Request $request){
        $this->checkout($request);
        return redirect()->route('dashboard');
    }
}
