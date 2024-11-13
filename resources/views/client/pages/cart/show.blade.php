@extends('app')

@section('content')
    <!--shopping cart area start -->
    <div class="shopping_cart_area mt-32">
        <div class="container">
            <form action="#">
                <div class="row">
                    <div class="col-12">
                        <div class="table_desc">
                            <div class="cart_page table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="product_remove">Delete</th>
                                            <th class="product_thumb">Image</th>
                                            <th class="product_name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product_quantity">Quantity</th>
                                            <th class="product_total">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart->items as $item)
                                            <tr>
                                                <td class="product_remove">
                                                    <form action="{{ route('cart.remove', $item->product->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');">
                                                            <i class="fa fa-trash-o"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                                <td class="product_thumb">
                                                    <a href="#">
                                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                                    </a>
                                                </td>
                                                <td class="product_name">
                                                    <a href="#">{{ $item->product->name }}</a>
                                                </td>
                                                <td class="product-price">
                                                    ${{ $item->product->price }}
                                                </td>
                                                <td class="product_quantity">
                                                    <label>Quantity</label>
                                                    <input type="number" min="1" value="{{ $item->quantity }}" class="quantity" data-id="{{ $item->product->id }}" style="width: 50px; text-align: center;">
                                                </td>
                                                <td class="product_total">
                                                    ${{ $item->product->price * $item->quantity }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>


                        </div>
                    </div>
                </div>

                <!--coupon code area start-->
                <div class="coupon_area">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="coupon_code left">
                                <h3>Coupon</h3>
                                <div class="coupon_inner">
                                    <p>Enter your coupon code if you have one.</p>
                                    <input placeholder="Coupon code" type="text">
                                    <button type="submit">Apply coupon</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="coupon_code right">
                                <h3>Cart Totals</h3>
                                <div class="coupon_inner">
                                    <div class="cart_subtotal">
                                        <p>Subtotal</p>
                                        <p class="cart_amount">${{ $cart->items->sum(function ($item) {
                                            return $item->product->price * $item->quantity;
                                        }) }}</p>
                                    </div>
                                    <div class="cart_subtotal ">
                                        <p>Shipping</p>
                                        <p class="cart_amount"><span>Flat Rate:</span> $10.00</p>
                                    </div>
                                    <a href="#">Calculate shipping</a>

                                    <div class="cart_subtotal">
                                        <p>Total</p>
                                        <p class="cart_amount">${{ $cart->items->sum(function ($item) {
                                            return $item->product->price * $item->quantity;
                                        }) + 10 }}</p> <!-- Add shipping here -->
                                    </div>
                                    <div class="checkout_btn">
                                        <a href="{{ route('checkout.index') }}">Proceed to Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--coupon code area end-->
            </form>
        </div>
    </div>

    <script>
        // Handle quantity update via AJAX
        document.querySelectorAll('.quantity').forEach(input => {
            input.addEventListener('change', function() {
                let productId = this.getAttribute('data-id');
                let quantity = parseInt(this.value);

                // Send AJAX request to update cart quantity
                updateCartItem(productId, quantity);
            });
        });

        function updateCartItem(productId, quantity) {
            fetch(`/cart/update/${productId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ quantity: quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    console.log(data.message);
                    location.reload(); // Reload page to update cart
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

@endsection
