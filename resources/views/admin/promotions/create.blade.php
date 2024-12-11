@extends('admin.app')

@section('content')
<h1>Tạo Khuyến Mãi Mới</h1>

<form action="{{ route('admin.promotions.store') }}" method="POST">
    @csrf

    <div class="form-group">
        <label for="title">Tiêu Đề</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="type">Loại</label>
        <select name="type" id="type" class="form-control" required>
            <option value="all_products">Tất cả sản phẩm</option>
            <option value="category">Danh mục</option>
            <option value="product">Sản phẩm</option>
            {{-- <option value="variant">Biến thể sản phẩm</option> --}}
        </select>
    </div>

    <div class="form-group">
        <label for="discount_percentage">Phần Trăm Giảm Giá</label>
        <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" required min="0" max="100">
    </div>

    <div class="form-group">
        <label for="start_date">Ngày Bắt Đầu</label>
        <input type="date" name="start_date" id="start_date" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="end_date">Ngày Kết Thúc</label>
        <input type="date" name="end_date" id="end_date" class="form-control" required>
    </div>

    <!-- Sản phẩm liên quan -->
    <div id="related-products" class="form-group" style="display: none;">
        <label for="products">Sản Phẩm Liên Quan</label>
        <select name="products" id="products" class="form-control">
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Danh mục liên quan (ẩn ban đầu) -->
    <div id="related-categories" class="form-group" style="display: none;">
        <label for="categories">Danh Mục Liên Quan</label>
        <select name="categories" id="categories" class="form-control">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>


    <!-- Trạng thái khuyến mãi -->
    <div class="form-group">
        <label for="status">Trạng Thái</label>
        <select name="status" id="status" class="form-control">
            <option value="1">Hoạt Động</option>
            <option value="0">Ngừng Hoạt Động</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Tạo Khuyến Mãi</button>
</form>

<script>
    // Lắng nghe sự thay đổi của dropdown 'Loại'
    document.getElementById('type').addEventListener('change', function() {
        var type = this.value;

        // Ẩn tất cả các phần liên quan
        document.getElementById('related-products').style.display = 'none';
        document.getElementById('related-categories').style.display = 'none';

        // Hiển thị các phần liên quan tương ứng với loại đã chọn
        if (type === 'product') {
            document.getElementById('related-products').style.display = 'block';
        } else if (type === 'category') {
            document.getElementById('related-categories').style.display = 'block';
        }
    });
</script>
@endsection
