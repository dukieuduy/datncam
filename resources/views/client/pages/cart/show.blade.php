@extends('app')

@section('content')
    <!-- Khu vực giỏ hàng bắt đầu -->
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
                                            <th class="product_remove">Xóa</th>
                                            <th class="product_thumb">Hình ảnh</th>
                                            <th class="product_name">Sản phẩm</th>
                                            <th class="product-price">Giá</th>
                                            <th class="product_quantity">Số lượng</th>
                                            <th class="product_total">Tổng cộng</th>
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
                                                        <img src="{{ filter_var($item->product->image_url, FILTER_VALIDATE_URL) ? $item->product->image_url : asset('images/default-product.jpg') }}" alt="{{ $item->product->name }}">
                                                    </a>
                                                </td>
                                                <td class="product_name">
                                                    <a href="#">{{ $item->product->name }}</a>
                                                </td>
                                                <td class="product-price">
                                                    ${{ $item->product->price }}
                                                </td>
                                                <td class="product_quantity">
                                                    <label>Số lượng</label>
                                                    <input type="number" min="1" value="{{ $item->quantity }}" class="quantity" data-id="{{ $item->product->id }}" style="width: 50px; text-align: center;">
                                                </td>
                                                <td class="product_total" data-price="{{ $item->product->price }}">
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

                <!-- Khu vực mã giảm giá bắt đầu -->
                <div class="coupon_area">
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <div class="coupon_code left">
                                <h3>Mã giảm giá</h3>
                                <div class="coupon_inner">
                                    <p>Nhập mã giảm giá của bạn nếu có.</p>
                                    <input placeholder="Nhập mã giảm giá" type="text">
                                    <button type="submit">Áp dụng mã</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="coupon_code right">
                                <h3>Tổng giỏ hàng</h3>
                                <div class="coupon_inner">
                                    <div class="cart_subtotal">
                                        <p>Tạm tính</p>
                                        <p class="cart_amount">${{ $cart->items->sum(function ($item) {
                                            return $item->product->price * $item->quantity;
                                        }) }}</p>
                                    </div>
                                    <div class="cart_subtotal">
                                        <p>Phí vận chuyển</p>
                                        <p class="cart_amount"><span>Đồng giá:</span> $10.00</p>
                                    </div>
                                    <a href="#">Tính phí vận chuyển</a>

                                    <div class="cart_subtotal">
                                        <p>Tổng cộng</p>
                                        <p class="cart_amount">${{ $cart->items->sum(function ($item) {
                                            return $item->product->price * $item->quantity;
                                        }) + 10 }}</p> <!-- Thêm phí vận chuyển vào đây -->
                                    </div>
                                    <div class="checkout_btn">
                                        <a href="{{ route('checkout.index') }}">Tiến hành thanh toán</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Khu vực mã giảm giá kết thúc -->
            </form>
        </div>
    </div>

    <script>
        // Xử lý cập nhật số lượng qua AJAX
        document.querySelectorAll('.quantity').forEach(input => {
            input.addEventListener('change', function() {
                let productId = this.getAttribute('data-id');
                let quantity = parseInt(this.value);

                // Gửi yêu cầu AJAX để cập nhật số lượng giỏ hàng
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
                    location.reload(); // Tải lại trang để cập nhật giỏ hàng
                }
            })
            .catch(error => console.error('Lỗi:', error));
        }
    </script>

@endsection
