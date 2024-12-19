@extends('app')
@section('content')
    <!-- Shopping Cart Area Start -->
    <div class="shopping_cart_area my-5">
        <div class="container">
            <form action="#">
                <div class="row">
                    <div class="col-12">
                        <!-- Cart Table -->
                        <div class="card shadow border-0">
                            <div class="card-header text-danger p-3">
                                <img
                                    src="https://static.vecteezy.com/system/resources/thumbnails/021/491/887/small_2x/shopping-cart-element-for-delivery-concept-png.png"
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
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($cartItems as $key)
                                            <tr>
                                                <td>
                                                    <span class="d-none cart_item_id">{{$key->id}}</span>
                                                    <img
                                                        src="{{ \Illuminate\Support\Facades\Storage::url($key->image) }}"
                                                        class="img-thumbnail"
                                                        style="width: 100px; height: 100px;" alt="Product">
                                                </td>
                                                <td>{{ $key->product_name }}</td>

                                                <td>
                                                    <div class="d-flex flex-column align-items-start">
                                                        <!-- Dropdown cho kích cỡ -->
                                                        <div class="mb-2">
                                                            <label for="size" class="form-label mb-1">Kích cỡ</label>
                                                            <select id="size" class="form-select form-select-sm"
                                                                    disabled>
                                                                <option value="">{{$key->size}}</option>
                                                                <!-- Thêm các lựa chọn khác nếu cần -->
                                                            </select>
                                                        </div>
                                                        <!-- Dropdown cho màu sắc -->
                                                        <div>
                                                            <label for="color" class="form-label mb-1">Màu sắc</label>
                                                            <select id="color" class="form-select form-select-sm"
                                                                    disabled>
                                                                <option value="">{{$key->color}}</option>
                                                                <!-- Thêm các lựa chọn khác nếu cần -->
                                                            </select>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($key->price, 0, ',', '.') }}đ</td>
                                                <td>
                                                    <input type="number" class="form-control w-50 quantity-input"
                                                           disabled
                                                           value="{{ $key->quantity }}" min="1" max="100"
                                                           data-price="{{ $key->price }}" onchange="updateCart(this)">
                                                </td>
                                                <td class="product_total">
                                                    <span
                                                        class="d-none price">{{number_format($key->price, 0, ',', '.')}}</span>
                                                    <span class="subtotal">{{ number_format($key->quantity * $key->price, 0, ',', '.')
                                                    }}</span>đ
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
                            <div class="row">
                                <div class="col-lg-6 mb-2">
                                    <div class="card shadow border-0">
                                        <div class="card-header text-danger">
                                            <img
                                                src="https://i.pinimg.com/originals/66/a6/6b/66a66bca51f6e2770ecfa63c40e97a0a.png"
                                                alt="coupon_icon" width="30" height="30">
                                            <span style="margin-left: 10px;">Mã giảm giá</span>
                                        </div>
                                        <div class="card-body">
                                            {{--                                            Nhập mã giảm giá của bạn:--}}

                                            {{--                                            <!-- Input cho mã giảm giá dạng văn bản -->--}}
                                            {{--                                            <input type="text" class="form-control mb-3" placeholder="Mã giảm giá"--}}
                                            {{--                                                   style="font-size: 18px;">--}}

                                            <!-- Thêm dòng mã giảm giá với các option -->
                                            <label for="discount-options">Mã giảm giá:</label>
                                            <select id="discount-options" class="form-control mb-3"
                                                    style="font-size: 17px;">
                                                <option value="0">Chọn mã giảm giá</option>
                                                @foreach($discounts as $discount)
                                                    <option value="{{$discount->id}}">
                                                        {{$discount->code}} -
                                                        giảm {{ number_format($discount->value) }} {{$discount->type == 'percentage' ? ' %' : ' vnđ'}},
                                                        đơn tối thiểu {{ number_format($discount->min_purchase_amount) }} vnđ
                                                        @if($discount->max_purchase_amount)
                                                            , giảm tối đa {{ number_format($discount->max_purchase_amount) }} vnđ
                                                        @endif
                                                    </option>
                                                @endforeach

                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-6 mb-2">
                                    <div class="card shadow border-0">
                                        <div class="card-header text-danger">
                                            <span style="margin-left: 10px;">Địa chỉ nhận hàng</span>
                                        </div>
                                        <div class="card-body">
                                            <label>
                                                <input type="radio" name="address" class="mx-2"
                                                       value="Liên Chung, Tân Yên, Bắc Giang">
                                                Liên Chung, Tân Yên, Bắc Giang
                                            </label> <br> <br>
                                            <label>
                                                <input type="radio" name="address" class="mx-2"
                                                       value="Nguyên Xá, Bắc Từ Liêm, Hà Nội">
                                                Nguyên Xá, Bắc Từ Liêm, Hà Nội
                                            </label>
                                            <br> <br>

                                            <label>
                                                <input type="radio" name="address" class="mx-2"
                                                       value="68/91/5/5 Đường Cầu Giấy, Hà Nội">
                                                68/91/5/5 Đường Cầu Giấy, Hà Nội
                                            </label>
                                        </div>
                                        <span class="text-danger mx-4" id="error_address"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-6 mb-2">
                                    <div class="card shadow border-0">
                                        <div class="card-header text-danger">
                                            <span style="margin-left: 10px;">Phương thức thanh toán</span>
                                        </div>
                                        <div class="card-body mx-3">
                                            <label>
                                                <input type="radio" name="payment_method" value="CASH">
                                                <img
                                                    src="https://thumbs.dreamstime.com/b/earn-money-vector-logo-icon-design-salary-symbol-design-hand-illustrations-earn-money-vector-logo-icon-design-salary-symbol-152893719.jpg"
                                                    alt="VNPay Logo" width="50" height="50">
                                                Thanh toán khi nhận hàng
                                            </label> <br> <br>
                                            <label>
                                                <input type="radio" name="payment_method" value="VNPAY" disabled>
                                                <img
                                                    src="https://vinadesign.vn/uploads/images/2023/05/vnpay-logo-vinadesign-25-12-57-55.jpg"
                                                    alt="VNPay Logo" width="50" height="50">
                                                Thanh toán bằng VNPay
                                            </label>
                                            <br> <br>

                                            <label>
                                                <input type="radio" name="payment_method" value="MOMO" disabled>
                                                <img
                                                    src="https://developers.momo.vn/v3/vi/assets/images/circle-a14ff76cbd316ccef146fa7deaaace2e.png"
                                                    alt="Momo Logo" width="50" height="50">
                                                Thanh toán bằng Momo
                                            </label>
                                            <br>
                                            <span class="text-danger" id="error_payment"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-2">
                                    <div class="card shadow border-0">
                                        <div class="card-header text-danger">
                                            <img
                                                src="https://images.emojiterra.com/microsoft/fluent-emoji/15.1/1024px/1f4b8_color.png"
                                                alt="total_money_icon" width="30" height="30">
                                            <span style="margin-left: 10px;">Tổng giỏ hàng</span>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <p class="mb-0">Tổng tiền:</p>
                                                <p class="mb-0 fw-bold cart_amount total">{{ number_format($totalAmount, 0, ',',
                                            '.') }} đ</p>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <p class="mb-0">Phí vận chuyển:</p>
                                                <p class="mb-0 fw-bold shipping-fee" data-shipping="55.000">55,000 đ</p>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <p class="mb-0">Mã giảm giá:</p>
                                                <p class="mb-0 fw-bold" id="discount_total">0 đ</p>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <p class="mb-0">Tổng cộng:</p>
                                                <p class="mb-0 fw-bold text-success ">
                                                    <span
                                                        class="total-amount">{{ number_format($totalAmount + 55000, 0, ',', '.') }}</span>
                                                    đ
                                                </p>
                                            </div>
                                            <div class="mt-3">
                                                <a id="checkout" data-url="{{ route('checkout') }}"
                                                   class="btn text-white w-100" style="background-color: #ff6600;">Thanh
                                                    toán</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Coupon Code Area End -->
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="/assets/js/checkout.js"></script>
@endsection
