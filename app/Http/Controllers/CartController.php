<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Facades\Session;
use App\Models\ProductVariationAttribute;

class CartController extends Controller
{
    public function index()
    {
        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!Auth::check()) {
            // Chuyển hướng đến trang đăng nhập
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để xem giỏ hàng.');
        }
        // Lấy giỏ hàng của người dùng
        $cart = Cart::where('user_id', Auth::id())->first();
    
        // Nếu không có giỏ hàng, trả về trang trống
        if (!$cart) {
            return view('client.pages.cart.index', ['cartItems' => [], 'totalAmount' => 0]);
        }
    
        // Lấy tất cả các sản phẩm trong giỏ hàng
        $cartItems = $cart->cartItems;
    
        // Tính tổng tiền trực tiếp trong controller
        $totalAmount = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;  // Tính tổng tiền cho từng sản phẩm
        });
    
        // Kiểm tra kết quả (để debug)
        // dd($cartItems, $totalAmount);
    
        // Trả về view với các dữ liệu
        return view('client.pages.cart.index', compact('cartItems', 'totalAmount'));
    }
    
    public function add(Request $request)
{
    $productId = $request->product_id;
    $selectedSize = $request->selectedSize;
    $selectedColor = $request->selectedColor;
    $quantity = $request->quantity;

    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng.');
    }

    // Lấy tất cả các biến thể của sản phẩm
    $productVariations = ProductVariation::where('product_id', $productId)->get();

    $matchedVariation = null;

    foreach ($productVariations as $variation) {
        // Lấy các thuộc tính của biến thể
        $variationAttributes = $variation->variationAttributes;

        // Lấy danh sách giá trị thuộc tính
        $attributeValues = ProductAttributeValue::whereIn(
            'id',
            $variationAttributes->pluck('product_attribute_value_id')
        )->pluck('value');

        // Kiểm tra xem kích thước và màu sắc có khớp không
        $sizeMatch = $attributeValues->contains($selectedSize);
        $colorMatch = $attributeValues->contains($selectedColor);

        if ($sizeMatch && $colorMatch) {
            $matchedVariation = $variation;
            break;
        }
    }

    // Nếu không tìm thấy biến thể phù hợp
    if (!$matchedVariation) {
        return redirect()->back()->with('error', 'Sản phẩm với màu sắc và kích thước đã chọn không tồn tại.');
    }

    $image = $matchedVariation->image;
    $price = $matchedVariation->price;

    // Kiểm tra hoặc tạo mới giỏ hàng cho người dùng
    $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $cartItem = CartItem::where('cart_id', $cart->id)
        ->where('product_id', $productId)
        ->where('size', $selectedSize)
        ->where('color', $selectedColor)
        ->first();

    if ($cartItem) {
        // Cập nhật số lượng nếu sản phẩm đã tồn tại
        $cartItem->quantity += $quantity;
        $cartItem->save();
        return redirect()->route('cart.index')->with('success', 'Số lượng sản phẩm đã được cập nhật.');
    }

    // Nếu sản phẩm chưa tồn tại trong giỏ hàng, thêm mới
    $cartItem = new CartItem([
        'cart_id' => $cart->id,
        'product_id' => $productId,
        'product_variation_id' => $matchedVariation->id, // Thêm dòng này
        'size' => $selectedSize,
        'color' => $selectedColor,
        'quantity' => $quantity,
        'price' => $price,
        'product_name' => $matchedVariation->product->name ?? 'Tên sản phẩm',
        'image' => $image,
    ]);
// dd($cartItem, $matchedVariation->id);

    $cartItem->save();

    return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
}

    
}
