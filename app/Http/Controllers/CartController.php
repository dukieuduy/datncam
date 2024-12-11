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
use App\Models\Promotion;

class CartController extends Controller
{

    public function index()
{
    $cart = Cart::where('user_id', Auth::id())->first();
    if (!$cart) {
        return view('client.pages.cart.index', ['cartItems' => [], 'totalAmount' => 0]);
    }

    $cartItems = $cart->cartItems;
    $totalAmount = 0;

    foreach ($cartItems as $item) {
        $product = $item->product;

        // Kiểm tra khuyến mãi của sản phẩm
        $promotion = Promotion::where(function ($query) use ($product) {
            $query->where('type', 'all_products')
                ->orWhere(function ($subQuery) use ($product) {
                    $subQuery->where('type', 'category')
                        ->whereHas('categories', function ($q) use ($product) {
                            $q->whereHas('products', function ($p) use ($product) {
                                $p->where('products.id', $product->id);
                            });
                        });
                })
                ->orWhere(function ($subQuery) use ($product) {
                    $subQuery->where('type', 'product')
                        ->whereHas('products', function ($q) use ($product) {
                            $q->where('products.id', $product->id);
                        });
                });
        })
        ->where('promotions.status', true)
        ->whereDate('promotions.start_date', '<=', now())
        ->whereDate('promotions.end_date', '>=', now())
        ->first();

        // Nếu có khuyến mãi, tính giá sau khuyến mãi
        if ($promotion) {
            $discountedPrice = $item->price * (1 - $promotion->discount_percentage / 100);
            $item->discounted_price = $discountedPrice;
        } else {
            $item->discounted_price = $item->price;
        }

        // Cập nhật tổng tiền
        $totalAmount += $item->discounted_price * $item->quantity;
    }

    return view('client.pages.cart.index', compact('cartItems', 'totalAmount'));
}




    public function add(Request $request)
    {
        $productId = $request->product_id;
        $selectedSize = $request->selectedSize;
        $selectedColor = $request->selectedColor;
        $quantity = $request->quantity;

        // Kiểm tra đăng nhập
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

            // Kiểm tra kích thước và màu sắc
            $sizeMatch = $attributeValues->contains($selectedSize);
            $colorMatch = $attributeValues->contains($selectedColor);

            if ($sizeMatch && $colorMatch) {
                $matchedVariation = $variation;
                break;
            }
        }

        // Nếu không tìm thấy biến thể
        if (!$matchedVariation) {
            return redirect()->back()->with('error', 'Sản phẩm với màu sắc và kích thước đã chọn không tồn tại.');
        }

        $image = $matchedVariation->image;
        $originalPrice = $matchedVariation->price;

        // Lấy khuyến mãi áp dụng
        $promotion = Promotion::where(function ($query) use ($productId) {
            $query->where('type', 'all_products')
                ->orWhere(function ($subQuery) use ($productId) {
                    $subQuery->where('type', 'category')
                        ->whereHas('categories', function ($q) use ($productId) {
                            $q->whereHas('products', function ($p) use ($productId) {
                                $p->where('products.id', $productId); // Chỉ định rõ bảng
                            });
                        });
                })
                ->orWhere(function ($subQuery) use ($productId) {
                    $subQuery->where('type', 'product')
                        ->whereHas('products', function ($q) use ($productId) {
                            $q->where('products.id', $productId); // Chỉ định rõ bảng
                        });
                });
        })
            ->where('promotions.status', true) // Chỉ định rõ bảng
            ->whereDate('promotions.start_date', '<=', now())
            ->whereDate('promotions.end_date', '>=', now())
            ->first();


        // Tính giá khuyến mãi (nếu có)
        $discountedPrice = $promotion ? $originalPrice * (1 - $promotion->discount_percentage / 100) : $originalPrice;

        // Kiểm tra hoặc tạo giỏ hàng
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Kiểm tra xem sản phẩm đã tồn tại trong giỏ hàng chưa
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

        // Thêm mới sản phẩm vào giỏ hàng
        $cartItem = new CartItem([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'product_variation_id' => $matchedVariation->id,
            'size' => $selectedSize,
            'color' => $selectedColor,
            'quantity' => $quantity,
            'price' => $discountedPrice, // Giá áp dụng khuyến mãi
            'product_name' => $matchedVariation->product->name ?? 'Tên sản phẩm',
            'image' => $image,
        ]);

        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }
}
