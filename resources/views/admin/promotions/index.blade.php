@extends('admin.app')

@section('content')
    <div class="container-fluid">
        <h1 class="text-center">Danh Sách Khuyến Mãi</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('admin.promotions.create') }}" class="btn btn-success mb-3">Tạo Khuyến Mãi Mới</a>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu Đề</th>
                    <th>Loại</th>
                    <th>Cụ thể</th>
                    <th>Giảm Giá</th>
                    <th>Ngày Bắt Đầu</th>
                    <th>Ngày Kết Thúc</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($promotions as $promotion)
                    <tr data-type="{{ $promotion->type }}">
                        <td>{{ $promotion->id }}</td>
                        <td>{{ $promotion->title }}</td>
                        <td>{{ ucfirst($promotion->type) }}</td>

                        <!-- Nếu là loại "category", hiển thị danh mục -->
                        <td>
                            @if ($promotion->type == 'category')
                                @foreach ($promotion->categories as $category)
                                    <span>{{ $category->name }}</span><br>
                                @endforeach
                            @elseif ($promotion->type == 'all_products')
                                <span>Tất cả sản phẩm</span>
                            @else
                                <!-- Hiển thị "Sản Phẩm Liên Quan" cho các loại khác -->
                                @foreach ($promotion->products as $product)
                                    <span>{{ $product->name }}</span><br>
                                @endforeach
                            @endif
                        </td>

                        <!-- Làm tròn số phần trăm và loại bỏ phần .00 -->
                        <td>{{ round($promotion->discount_percentage) }}%</td>

                        <td>{{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') }}</td>

                        <td>
                            <span class="badge {{ $promotion->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $promotion->status ? 'Hoạt Động' : 'Ngừng Hoạt Động' }}
                            </span>
                        </td>

                        <td>
                            <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-primary">Sửa</a>
                            <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST"
                                style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
