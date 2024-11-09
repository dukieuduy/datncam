
<h1>Chi tiết giỏ hàng #{{ $cart->id }}</h1>

<p><strong>User ID:</strong> {{ $cart->user_id ?? 'Guest' }}</p>
<p><strong>Ngày Tạo:</strong> {{ $cart->created_at }}</p>

<h3>Sản phẩm trong giỏ hàng</h3>
<table>
    <thead>
        <tr>
            <th>Hình Ảnh</th>
            <th>Tên Sản Phẩm</th>
            <th>Số Lượng</th>
            <th>Giá</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cart->items as $item)
            <tr>
                <td>
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" width="100">
                </td>
                <td>{{ $item->product->name }}</td>
                <td>
                    <button class="btn-decrease" data-id="{{ $item->product->id }}">-</button>
                    <input type="text" value="{{ $item->quantity }}" data-id="{{ $item->product->id }}" class="quantity" style="width: 50px; text-align: center;">
                    <button class="btn-increase" data-id="{{ $item->product->id }}">+</button>
                </td>
                <td>${{ $item->product->price * $item->quantity }}</td>
                <td>
                    <form action="{{ route('cart.remove', $item->product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Nút thanh toán -->
<div style="margin-top: 20px;">
    <h4>Tổng số lượng: {{ $totalQuantity }}</h4>
    <h4>Tổng giá: ${{ $cart->items->sum(function ($item) {
        return $item->product->price * $item->quantity;
    }) }}</h4>

<a href="{{ route('checkout.index') }}" class="btn btn-success">Thanh Toán</a>

</div>

<script>
    document.querySelectorAll('.btn-increase').forEach(button => {
        button.addEventListener('click', function() {
            let productId = this.getAttribute('data-id');
            let quantityInput = document.querySelector(`.quantity[data-id="${productId}"]`);
            let quantity = parseInt(quantityInput.value) + 1;
            quantityInput.value = quantity;

            // Gửi yêu cầu AJAX để cập nhật số lượng
            updateCartItem(productId, quantity);
        });
    });

    document.querySelectorAll('.btn-decrease').forEach(button => {
        button.addEventListener('click', function() {
            let productId = this.getAttribute('data-id');
            let quantityInput = document.querySelector(`.quantity[data-id="${productId}"]`);
            let quantity = parseInt(quantityInput.value);

            if (quantity > 1) {
                quantity--;
                quantityInput.value = quantity;

                // Gửi yêu cầu AJAX để cập nhật số lượng
                updateCartItem(productId, quantity);
            }
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
        .catch(error => console.error('Error:', error));
    }
</script>
