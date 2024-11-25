@extends('app')
@section('content')
<!-- Shopping Cart Area Start -->
<div class="shopping_cart_area mt-5">
    <div class="container">
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
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
                                            <th scope="col">
                                                <input type="checkbox" class="form-check-input" id="select-all">
                                            </th>
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
                                        @foreach($cartItems as $key)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input item-checkbox"
                                                    value="{{ $key->id }}">
                                            </td>
                                            <td>
                                                <img src="{{ asset('storage/' . $key->image) }}" class="img-thumbnail"
                                                    style="width: 100px; height: 100px;" alt="Product">
                                            </td>
                                            <td>{{ $key->product_name }}</td>

                                            <td>
    <div class="d-flex flex-column align-items-start">
        <!-- Dropdown cho kích cỡ -->
        <div class="mb-2">
            <label for="size" class="form-label mb-1">Kích cỡ</label>
            <select id="size" class="form-select form-select-sm">
                <option value="">{{$key->size}}</option>
                <!-- Thêm các lựa chọn khác nếu cần -->
            </select>
        </div>
        <!-- Dropdown cho màu sắc -->
        <div>
            <label for="color" class="form-label mb-1">Màu sắc</label>
            <select id="color" class="form-select form-select-sm">
                <option value="">{{$key->color}}</option>
                <!-- Thêm các lựa chọn khác nếu cần -->
            </select>
        </div>
    </div>
</td>

                                            
                                            
                                            <td>{{ number_format($key->price, 0, ',', '.') }}đ</td>
                                            <td>
                                                <input type="number" class="form-control w-50 quantity-input"
                                                    value="{{ $key->quantity }}" min="1" max="100"
                                                    data-price="{{ $key->price }}" onchange="updateCart(this)">
                                            </td>
                                            <td class="product_total">
                                                <span>{{ number_format($key->quantity * $key->price, 0, ',', '.')
                                                    }}</span>đ
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

                                    <!-- Input cho mã giảm giá dạng văn bản -->
                                    <input type="text" class="form-control mb-3" placeholder="Mã giảm giá"
                                        style="font-size: 18px;">

                                    <!-- Thêm dòng mã giảm giá với các option -->
                                    <label for="discount-options">Mã giảm giá đã lưu:</label>
                                    <select id="discount-options" class="form-control mb-3" style="font-size: 17px;">
                                        <option value="">Chọn mã giảm giá</option>
                                        <option value="DISCOUNT10">DISCOUNT10 - Giảm 10%</option>
                                        <option value="DISCOUNT20">DISCOUNT20 - Giảm 20%</option>
                                        <option value="DISCOUNT30">DISCOUNT30 - Giảm 30%</option>
                                    </select>

                                    <!-- Button Áp dụng -->
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
                                        <p class="mb-0 fw-bold cart_amount total">{{ number_format($totalAmount, 0, ',',
                                            '.') }}đ</p>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-0">Phí vận chuyển:</p>
                                        <p class="mb-0 fw-bold shipping-fee" data-shipping="55000">55,000đ</p>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-0">Tổng cộng:</p>
                                        <p class="mb-0 fw-bold text-success total-amount">{{ number_format($totalAmount
                                            + 55000, 0, ',', '.') }}đ</p>
                                    </div>
                                    <div class="mt-3">
                                        <a href="#" class="btn text-white w-100" style="background-color: #ff6600;"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal">Thanh toán</a>
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
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Phương thức thanh toán</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label>
                    <input type="radio" name="payment_method">
                    <img src="https://thumbs.dreamstime.com/b/earn-money-vector-logo-icon-design-salary-symbol-design-hand-illustrations-earn-money-vector-logo-icon-design-salary-symbol-152893719.jpg"
                        alt="VNPay Logo" width="50" height="50">
                    Thanh toán khi nhận hàng
                </label> <br> <br>
                <label>
                    <input type="radio" name="payment_method">
                    <img src="https://vinadesign.vn/uploads/images/2023/05/vnpay-logo-vinadesign-25-12-57-55.jpg"
                        alt="VNPay Logo" width="50" height="50">
                    Thanh toán bằng VNPay
                </label>
                <br> <br>

                <label>
                    <input type="radio" name="payment_method">
                    <img src="https://developers.momo.vn/v3/vi/assets/images/circle-a14ff76cbd316ccef146fa7deaaace2e.png"
                        alt="Momo Logo" width="50" height="50">
                    Thanh toán bằng Momo
                </label>
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Tiếp tục</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
            </div>
        </div>
    </div>
</div>
<script>
    // Hàm xử lý chọn tất cả checkbox
    document.getElementById('select-all').addEventListener('change', function () {
        const isChecked = this.checked; // Lấy trạng thái checkbox "Chọn tất cả"
        const checkboxes = document.querySelectorAll('.item-checkbox'); // Tất cả checkbox con

        // Gán trạng thái cho các checkbox con
        checkboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });
    });

    // Hàm cập nhật trạng thái "Chọn tất cả" khi thay đổi checkbox con
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            const allChecked = Array.from(document.querySelectorAll('.item-checkbox'))
                .every(checkbox => checkbox.checked); // Kiểm tra nếu tất cả được chọn
            document.getElementById('select-all').checked = allChecked; // Cập nhật trạng thái "Chọn tất cả"
        });
    });

    // Hàm cập nhật giỏ hàng
    function updateCart(input) {
        const quantity = parseInt(input.value); // Lấy số lượng mới
        const price = parseInt(input.dataset.price); // Lấy giá sản phẩm
        const total = quantity * price; // Tính thành tiền

        // Cập nhật thành tiền trên giao diện
        const totalElement = input.closest('tr').querySelector('.product_total span');
        totalElement.textContent = total.toLocaleString('vi-VN');

        // Cập nhật tổng tiền giỏ hàng
        updateCartTotal();
    }

    // Hàm tính tổng tiền giỏ hàng
    function updateCartTotal() {
        let totalAmount = 0;

        // Duyệt qua từng sản phẩm và tính tổng
        document.querySelectorAll('.quantity-input').forEach(input => {
            const quantity = parseInt(input.value);
            const price = parseInt(input.dataset.price);
            totalAmount += quantity * price;
        });

        // Lấy phí vận chuyển
        const shippingFee = 55000;

        // Hiển thị tổng tiền giỏ hàng
        const totalAmountElement = document.querySelector('.cart_amount.total');
        totalAmountElement.textContent = totalAmount.toLocaleString('vi-VN') + 'đ';

        // Hiển thị tổng cộng (tổng tiền + phí vận chuyển)
        const totalWithShippingElement = document.querySelector('.total-amount');
        totalWithShippingElement.textContent = (totalAmount + shippingFee).toLocaleString('vi-VN') + 'đ';
    }
</script>
<br>
<br>
<br>

@endsection