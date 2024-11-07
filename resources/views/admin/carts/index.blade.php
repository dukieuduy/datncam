

<h1>Quản Lý Giỏ Hàng</h1>

<table>
    <thead>
        <tr>
            <th>ID Giỏ Hàng</th>
            <th>User ID</th>
            <th>Ngày Tạo</th>
            <th>Sản Phẩm</th>
            <th>Thao Tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($carts as $cart)
            <tr>
                <td>{{ $cart->id }}</td>
                <td>{{ $cart->user_id ?? 'Khách' }}</td>
                <td>{{ $cart->created_at }}</td>
                <td>
                    @foreach ($cart->items as $item)
                        {{ $item->product->name }} ({{ $item->quantity }})<br>
                    @endforeach
                </td>

                <td>
                    <a href="{{ route('admin.cart.show', $cart->id) }}" class="btn btn-info">Xem Chi Tiết</a>
                    <form action="{{ route('admin.cart.destroy', $cart->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa giỏ hàng này?');">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
