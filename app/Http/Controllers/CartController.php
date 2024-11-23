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
//     public function addToCart(Request $request)
// {
//     $product_id = $request->input('product_id');
//     $quantity = $request->input('quantity', 1);

//     // Kiểm tra nếu người dùng đã đăng nhập
//     if (auth()->check()) {
//         $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
//     } else {
//         // Đối với người dùng khách, sử dụng session
//         $cart_id = Session::get('cart_id');

//         if (!$cart_id) {
//             // Tạo mới giỏ hàng và lưu vào session
//             $cart = Cart::create();
//             Session::put('cart_id', $cart->id);
//         } else {
//             // Tìm giỏ hàng theo cart_id trong session
//             $cart = Cart::find($cart_id);
//         }
//     }

//     // Nếu cart không tồn tại (trường hợp hiếm), tạo một cart mới
//     if (!$cart) {
//         $cart = Cart::create();
//         Session::put('cart_id', $cart->id);
//     }

//     // Thêm hoặc cập nhật sản phẩm trong giỏ hàng
//     $cartItem = CartItem::updateOrCreate(
//         ['cart_id' => $cart->id, 'product_id' => $product_id],
//         ['quantity' => DB::raw("quantity + $quantity")]
//     );

//     return response()->json(['message' => 'Sản phẩm đã được thêm vào giỏ hàng thành công']);
// }

        // Hiển thị giỏ hàng
        // public function showCart()
        // {
        //     $cart = null;

        //     if (auth()->check()) {
        //         $cart = Cart::where('user_id', auth()->id())->with('items.product')->first();
        //     } else {
        //         $cart_id = Session::get('cart_id');
        //         if ($cart_id) {
        //             $cart = Cart::where('id', $cart_id)->with('items.product')->first();
        //         }
        //     }

        //         if (!$cart) {
        //             return response()->json(['message' => 'Cart is empty']);
        //         }

        //         $totalQuantity = $cart->items->sum('quantity');
        //         return view('client.pages.cart.show', compact('cart', 'totalQuantity'));
        // }


   

        // Sửa số lượng sản phẩm trong giỏ hàng
        // public function updateItem(Request $request, $productId)
        // {
        //     $validated = $request->validate([
        //         'quantity' => 'required|integer|min:1',
        //     ]);

        //     $cart = null;

        //     if (auth()->check()) {
        //         $cart = Cart::where('user_id', auth()->id())->first();
        //     } else {
        //         $cart_id = Session::get('cart_id');
        //         if ($cart_id) {
        //             $cart = Cart::find($cart_id);
        //         }
        //     }

        //     if (!$cart) {
        //         return response()->json(['message' => 'Cart is empty'], 404);
        //     }

        //     // Cập nhật số lượng sản phẩm
        //     $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
        //     if ($cartItem) {
        //         $cartItem->quantity = $validated['quantity'];
        //         $cartItem->save();
        //         return response()->json(['message' => 'Đã cập nhật thành công mục giỏ hàng']);
        //     }

        //     return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng'], 404);
        // }

        // // Xóa sản phẩm khỏi giỏ hàng
        // public function removeItem($productId)
        // {
        //     $cart = null;

        //     if (auth()->check()) {
        //         $cart = Cart::where('user_id', auth()->id())->first();
        //     } else {
        //         $cart_id = Session::get('cart_id');
        //         if ($cart_id) {
        //             $cart = Cart::find($cart_id);
        //         }
        //     }

        //     if (!$cart) {
        //         return response()->json(['message' => 'Cart is empty'], 404);
        //     }

        //     // Xóa sản phẩm khỏi giỏ hàng
        //     $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
        //     if ($cartItem) {
        //         $cartItem->delete();
        //         return redirect()->back()->with('message', 'Đã xóa thành công mục giỏ hàng');
        //     }

        //     return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng'], 404);
        // }

    //checkout


    public function index()
    {
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
    
    // public function add(Request $request)
    // {
    //     $productId = $request->product_id;
    //     $selectedSize = $request->selectedSize;
    //     $selectedColor = $request->selectedColor;
    
    //     // Bước 1: Lấy các dòng trong bảng product_variations
    //     $productVariations = ProductVariation::where('product_id', $productId)->get();
    
    //     // Khởi tạo biến chứa kết quả
    //     $matchedVariation = null;
    
    //     // Lặp qua từng biến thể để tìm biến thể phù hợp
    //     foreach ($productVariations as $variation) {
    //         // Bước 2: Lấy các dòng trong bảng product_variation_attributes
    //         $variationAttributes = ProductVariationAttribute::where('product_variation_id', $variation->id)->get();
    
    //         // Bước 3: Lấy các dòng trong bảng product_attribute_values
    //         $attributeValues = ProductAttributeValue::whereIn(
    //             'id',
    //             $variationAttributes->pluck('product_attribute_value_id') // Lấy tất cả product_attribute_value_id
    //         )->get();
    
    //         // Bước 4: So sánh $selectedSize và $selectedColor với giá trị `value`
    //         $sizeMatch = $attributeValues->contains('value', $selectedSize);
    //         $colorMatch = $attributeValues->contains('value', $selectedColor);
    
    //         // Nếu cả size và color đều khớp, lưu biến thể hiện tại
    //         if ($sizeMatch && $colorMatch) {
    //             $matchedVariation = $variation;
    //             break; // Thoát vòng lặp vì đã tìm thấy biến thể phù hợp
    //         }
    //     }
    
    //     // Kiểm tra kết quả
    //     if ($matchedVariation) {
    //         // Bước 5: Lấy ảnh và giá của biến thể
    //         $image = $matchedVariation->image;
    //         $price = $matchedVariation->price;
        
    //     } else {
    //         return redirect()->back()->with('error', 'Hiện tại sản phẩm không có màu sắc và size như bạn chọn, vui lòng chọn màu sắc và size khác!');

    //     }
    // }
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
