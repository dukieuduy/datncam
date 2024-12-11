@extends('app')
@section('content')
    <!-- Shopping Cart Area Start -->
    <div class="shopping_cart_area mt-5">
        <div class="container">
            <form action="#">
                <div class="row">
                    <div class="col-12">
                        <!-- Cart Table -->
                        <div class="card shadow border-0">
                            <div class="card-header text-danger p-3">
                                <img src="https://static.vecteezy.com/system/resources/thumbnails/021/491/887/small_2x/shopping-cart-element-for-delivery-concept-png.png"
                                    alt="cart_icon" width="40" height="40">
                                <span style="margin-left: 10px;">Giỏ hàng của: <b>{{ Auth::user()->name }}</b> </span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">Ảnh</th>
                                                <th scope="col">Tên sản phẩm</th>
                                                <th scope="col">Phân loại</th>
                                                <th scope="col">Giá</th>
                                                <th scope="col">Số lượng</th>
                                                <th scope="col">Thành tiền</th>
                                                <th scope="col">Xóa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cartItems as $item)
                                                <tr>
                                                    <td>
                                                        <img src="{{ asset('storage/' . $item->image) }}"
                                                            class="img-thumbnail" style="width: 100px; height: 100px;"
                                                            alt="Product">
                                                    </td>
                                                    <td>{{ $item->product_name }}</td>
                                                    <td>
                                                        <div class="d-flex flex-column align-items-start">
                                                            <div class="mb-2">
                                                                <label for="size" class="form-label mb-1">Kích
                                                                    cỡ</label>
                                                                <select id="size" class="form-select form-select-sm">
                                                                    <option value="">{{ $item->size }}</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label for="color" class="form-label mb-1">Màu
                                                                    sắc</label>
                                                                <select id="color" class="form-select form-select-sm">
                                                                    <option value="">{{ $item->color }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if ($item->discounted_price < $item->price)
                                                            <!-- Nếu có khuyến mãi, hiển thị giá cũ gạch ngang và giá khuyến mãi -->
                                                            <span style="text-decoration: line-through;">
                                                                {{ number_format($item->price, 0, ',', '.') }}đ
                                                            </span>
                                                            <br>
                                                            <strong>{{ number_format($item->discounted_price, 0, ',', '.') }}đ</strong>
                                                        @else
                                                            <!-- Nếu không có khuyến mãi, chỉ hiển thị giá gốc -->
                                                            <strong>{{ number_format($item->price, 0, ',', '.') }}đ</strong>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <input type="number" class="form-control w-50 quantity-input"
                                                            value="{{ $item->quantity }}" min="1" max="100"
                                                            data-price="{{ $item->price }}" onchange="updateCart(this)">
                                                    </td>
                                                    <td class="product_total">
                                                        <span>{{ number_format($item->quantity * $item->discounted_price, 0, ',', '.') }}</span>đ
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-outline-danger btn-sm"><i
                                                                class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Coupon Code Area -->
                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <div class="card shadow border-0">
                                    <div class="card-header text-danger">
                                        <img src="https://i.pinimg.com/originals/66/a6/6b/66a66bca51f6e2770ecfa63c40e97a0a.png"
                                            alt="coupon_icon" width="30" height="30">
                                        <span style="margin-left: 10px;">Mã giảm giá</span>
                                    </div>
                                    <div class="card-body">
                                        Nhập mã giảm giá của bạn:
                                        <input type="text" class="form-control mb-3" placeholder="Mã giảm giá"
                                            style="font-size: 18px;">
                                        <label for="discount-options">Mã giảm giá đã lưu:</label>
                                        <select id="discount-options" class="form-control mb-3" style="font-size: 17px;">
                                            <option value="">Chọn mã giảm giá</option>
                                            <option value="DISCOUNT10">DISCOUNT10 - Giảm 10%</option>
                                            <option value="DISCOUNT20">DISCOUNT20 - Giảm 20%</option>
                                            <option value="DISCOUNT30">DISCOUNT30 - Giảm 30%</option>
                                        </select>
                                        <button type="submit" class="btn text-white" style="background-color: #00CC99;">Áp
                                            dụng</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card shadow border-0">
                                    <div class="card-header text-danger">
                                        <img src="https://images.emojiterra.com/microsoft/fluent-emoji/15.1/1024px/1f4b8_color.png"
                                            alt="total_money_icon" width="30" height="30">
                                        <span style="margin-left: 10px;">Tổng giỏ hàng</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-0">Tổng tiền:</p>
                                            <p class="mb-0 fw-bold cart_amount total">
                                                {{ number_format($totalAmount, 0, ',', '.') }}đ
                                            </p>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-0">Phí vận chuyển:</p>
                                            <p class="mb-0 fw-bold shipping-fee" data-shipping="55000">55,000đ</p>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <p class="mb-0">Tổng cộng:</p>
                                            <p class="mb-0 fw-bold text-success total-amount">
                                                {{ number_format($totalAmount + 55000, 0, ',', '.') }}đ
                                            </p>
                                        </div>
                                        <div class="mt-3">
                                            <a href="#" class="btn text-white w-100"
                                                style="background-color: #ff6600;" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal">Thanh toán</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
