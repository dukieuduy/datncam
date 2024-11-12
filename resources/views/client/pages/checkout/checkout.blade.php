<h1>Thanh Toán</h1>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<h3>Chi tiết giỏ hàng</h3>
<table>
    <thead>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cart->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>${{ $item->product->price }}</td>
                <td>${{ $item->product->price * $item->quantity }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<h4>Tổng tiền: ${{ $cart->items->sum(function ($item) {return $item->product->price * $item->quantity;}) }}</h4>

<form action="" method="POST">
    {{-- {{ route('checkout.process') }} --}}
    @csrf
    <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
</form>
