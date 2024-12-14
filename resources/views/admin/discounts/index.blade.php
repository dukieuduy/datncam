@extends('admin.app')

@section('content')
    <div class="container-fluid">
        <h1>Danh Sách Mã Giảm Giá</h1>
        <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">Tạo Mã Giảm Giá Mới</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Mã Giảm Giá</th>
                    <th>Loại Giảm Giá</th>
                    <th>Giá Trị</th>
                    <th>Ngày Bắt Đầu</th>
                    <th>Ngày Kết Thúc</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($discounts as $discount)
                    <tr>
                        <td>{{ $discount->code }}</td>
                        <td>
                            @switch($discount->discount_type)
                                @case('percentage')
                                    Phần Trăm
                                    @break
                                @case('fixed')
                                    Giảm Cố Định
                                    @break
                                @default
                                    Không Xác Định
                            @endswitch
                        </td>
                        <td>
                            {{ number_format($discount->value, 0, ',', '.') }}
                            {{ $discount->discount_type == 'percentage' ? '%' : 'VNĐ' }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($discount->start_date)->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($discount->end_date)->format('d/m/Y H:i') }}</td>
                        <td>
                            @if ($discount->is_active)
                                <span class="badge bg-success">Kích Hoạt</span>
                            @else
                                <span class="badge bg-warning">Không Kích Hoạt</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.discounts.show', $discount->id) }}" class="btn btn-info btn-sm">Xem</a>
                            <a href="{{ route('admin.discounts.edit', $discount->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                            <form action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
