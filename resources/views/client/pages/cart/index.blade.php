@extends('app')

@section('content')
<!-- Shopping Cart Area Start -->
<div class="shopping_cart_area my-5">
    <div class="container">
        <!-- Success Message after Deleting -->
        @if (session('success'))
        <div id="successMessage" class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('confirm_checkout') }}" method="post">
            @csrf
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
                                <table class="table table-hover align-middle mb-0" style="table-layout: fixed; width: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 80px;"> <!-- Giảm chiều rộng cột checkbox -->
                                                <input type="checkbox" class="form-check-input" id="select-all">
                                            </th>
                                            <th scope="col" style="width: 120px;">Sản phẩm</th> <!-- Giảm chiều rộng cột ảnh -->
                                            <th scope="col" style="width: 100px;">Phân loại</th> <!-- Giảm chiều rộng cột phân loại -->
                                            <th scope="col" style="width: 80px;">Giá</th> <!-- Giảm chiều rộng cột giá -->
                                            <th scope="col" style="width: 80px;">Số lượng</th> <!-- Giảm chiều rộng cột số lượng -->
                                            <th scope="col" style="width: 80px;">Thành tiền</th> <!-- Giảm chiều rộng cột thành tiền -->
                                            <th scope="col" style="width: 80px; min-width: 80px;">Xóa</th> <!-- Cố định chiều rộng cột xóa -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cartItems as $key)
                                        <tr>
                                            <td style="width: 50px;">
                                                <input type="checkbox" class="form-check-input item-checkbox" name="cart_item_id[]"
                                                    value="{{ $key->id }}">
                                            </td>
                                            <td style="width: 80px;">
                                            <p class="mb-1" style="text-align: center; font-size: 14px; font-weight: bold;">{{ $key->product_name }}</p>

                                                <img src="{{ \Illuminate\Support\Facades\Storage::url($key->image) }} "
                                                     class="img-thumbnail"
                                                     style="width: 100px; height: 100px; max-width: 100%; height: auto;" alt="Product">
                                            </td>

                                            <td style="width: 180px;">
                                                <div class="d-flex flex-column align-items-start">
                                                    <div class="mb-2">
                                                        <label for="size" class="form-label mb-1">Kích cỡ</label>
                                                        <select id="size" class="form-select form-select-sm" style="width: 100%; max-width: 150px;">
                                                            <option value="">{{$key->size}}</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label for="color" class="form-label mb-1">Màu sắc</label>
                                                        <select id="color" class="form-select form-select-sm" style="width: 100%; max-width: 150px;">
                                                            <option value="">{{$key->color}}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width: 80px;">{{ number_format($key->price, 0, ',', '.') }}đ</td>
                                            <td style="width: 120px;">
                                                <input type="number" class="form-control w-50 quantity-input"
                                                       value="{{ $key->quantity }}" min="1" max="100"
                                                       data-price="{{ $key->price }}" onchange="updateCart(this)" style="width: 100%;"> 
                                            </td>
                                            <td class="product_total" style="width: 80px;">
                                                <span>{{ number_format($key->quantity * $key->price, 0, ',', '.') }}</span>đ
                                            </td>
                                            <td style="width: 80px; min-width: 80px;">
                                                <!-- Delete Button to Trigger Modal -->
                                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-item-id="{{ $key->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
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
                        <div class="col-lg-6 mt-3">
                            <div class="card shadow border-0">
                                <div class="card-header text-danger">
                                    <img src="https://images.emojiterra.com/microsoft/fluent-emoji/15.1/1024px/1f4b8_color.png"
                                         alt="total_money_icon" width="30" height="30">
                                    <span style="margin-left: 10px;">Tổng giỏ hàng</span>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-0">Tổng tiền:</p>
                                        <p class="mb-0 fw-bold cart_amount total" id="totalAmount">{{ number_format($totalAmount, 0, ',', '.') }}đ</p>
                                    </div>

                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-0">Phí vận chuyển:</p>
                                        <p class="mb-0 fw-bold shipping-fee" data-shipping="55000">55,000đ</p>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-0">Tổng cộng:</p>
                                        <p class="mb-0 fw-bold text-success ">
                                            <span class="total-amount">{{ number_format($totalAmount + 55000, 0, ',', '.') }}</span> đ
                                        </p>
                                    </div>
                                    <div class="mt-3">
                                        <button type="submit" class="btn text-white w-100" id="next_btn" data-url="{{ route('confirm_checkout') }}" style="background-color: #ff6600;">Thanh toán</button>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <img src="https://cdn-icons-png.flaticon.com/512/10608/10608888.png"
                     alt="" srcset="" width="20px" height="20px" style="margin-right: 10px;">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn ơi, bạn có thực sự muốn xóa mình khỏi giỏ hàng?
                <img src="https://png.pngtree.com/png-clipart/20220928/original/pngtree-sad-cute-cartoon-facial-expression-png-image_8639691.png"
                     alt="" width="25px" height="25px" style="margin-left: 5px;">
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="/assets/js/cart.js"></script>
<script>
    // Set the delete item URL when the modal is triggered
    var deleteModal = document.getElementById('deleteConfirmationModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget; // Button that triggered the modal
        var itemId = button.getAttribute('data-item-id'); // Extract the item ID
        var form = document.getElementById('deleteForm');
        console.log(itemId); // Log the itemId to check if it's being set correctly

        // Set the correct action URL for the delete form
        form.action = '/cart/remove/' + itemId; // Set the action for the delete form
    });

    // Auto hide the success message after 15 seconds
    setTimeout(function() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 3000);
    
</script>
@endsection
