@extends('admin.app')

@section('content')
    <div class="container-fluid">
        <h1>Chi Tiết Mã Giảm Giá</h1>

        <table class="table">
            <tr>
                <th>Mã Giảm Giá</th>
                <td>{{ $discount->code }}</td>
            </tr>
            <tr>
                <th>Loại Giảm Giá</th>
                <td>{{ $discount->discount_type == 'percentage' ? 'Phần Trăm' : 'Giảm Cố Định' }}</td>
            </tr>
            <tr>
                <th>Giá Trị Giảm Giá</th>
                <td>
                    @if ($discount->discount_type == 'percentage')
                        {{ number_format($discount->value, 0, ',', '.') }} %
                    @else
                        {{ number_format($discount->value, 0, ',', '.') }} VNĐ
                    @endif
                </td>
            </tr>
            <tr>
                <th>Số Tiền Giảm Tối Đa</th>
                <td>{{ $discount->max_discount_amount ? number_format($discount->max_discount_amount, 0, ',', '.') . ' VNĐ' : 'Không giới hạn' }}</td>
            </tr>
            <tr>
                <th>Số Tiền Mua Tối Thiểu</th>
                <td>{{ number_format($discount->min_purchase_amount, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <th>Số Lần Sử Dụng Tối Đa</th>
                <td>{{ $discount->usage_limit }}</td>
            </tr>
            <tr>
                <th>Giới Hạn Sử Dụng Mỗi Người Dùng</th>
                <td>{{ $discount->usage_per_user }}</td>
            </tr>
            <tr>
                <th>Ngày Bắt Đầu</th>
                <td>{{ \Carbon\Carbon::parse($discount->start_date)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Ngày Kết Thúc</th>
                <td>{{ \Carbon\Carbon::parse($discount->end_date)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Phạm Vi Giảm Giá</th>
                <td>
                    @switch($discount->discount_scope)
                        @case('user')
                            Người Dùng
                            @break
                        @case('product')
                            Sản Phẩm
                            @break
                        @case('category')
                            Danh Mục
                            @break
                        @case('all')
                            Toàn Bộ
                            @break
                        @default
                            Chưa Xác Định
                    @endswitch
                </td>
            </tr>
            <tr>
                <th>Kích Hoạt</th>
                <td>{{ $discount->is_active ? 'Có' : 'Không' }}</td>
            </tr>

            @if ($discount->discount_scope === 'product' && $discount->products->isNotEmpty())
                <tr>
                    <th>Sản Phẩm Liên Quan</th>
                    <td>
                        <ul>
                            @foreach($discount->products as $product)
                                <li>{{ $product->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endif

            @if ($discount->discount_scope === 'category' && $discount->categories->isNotEmpty())
                <tr>
                    <th>Danh Mục Liên Quan</th>
                    <td>
                        <ul>
                            @foreach($discount->categories as $category)
                                <li>{{ $category->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endif

            @if ($discount->discount_scope === 'user' && $discount->users->isNotEmpty())
                <tr>
                    <th>Người Dùng Liên Quan</th>
                    <td>
                        <ul>
                            @foreach($discount->users as $user)
                                <li>{{ $user->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endif
        </table>

       <div style="justify-content: center">
        <a href="{{ route('admin.discounts.index') }}" class="btn btn-info btn-bm">Quay lại</a>
        <a href="{{ route('admin.discounts.edit', $discount->id) }}" class="btn btn-warning btn-bm">Sửa</a>
       </div>
    </div>
@endsection
