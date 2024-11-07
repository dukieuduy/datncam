

<h1>Chi tiết giỏ hàng #{{ $cart->id }}</h1>
<p><strong>User ID:</strong> {{ $cart->user_id ?? 'Guest' }}</p>
<p><strong>Ngày Tạo:</strong> {{ $cart->created_at }}</p>

<h3>Sản phẩm trong giỏ hàng</h3>
<table>
    <thead>
        <tr>
            <th>Tên Sản Phẩm</th>
            <th>Số Lượng</th>
            <th>Giá</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cart->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ $item->product->price * $item->quantity }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div>
    <h4>Tổng số lượng: {{ $cart->items->sum('quantity') }}</h4>
    <h4>Tổng giá: ${{ $cart->items->sum(function ($item) {
        return $item->product->price * $item->quantity;
    }) }}</h4>
</div>
<a href="{{ route('admin.carts.index') }}">back</a>

