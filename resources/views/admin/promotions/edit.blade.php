@extends('admin.app')
@section('content')
    <h1>Sửa Khuyến Mãi</h1>

    <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Tiêu Đề</label>
            <input type="text" name="title" id="title" class="form-control"
                value="{{ old('title', $promotion->title) }}" required>
        </div>

        <div class="form-group">
            <label for="type">Loại</label>
            <select name="type" id="type" class="form-control" required>
                <option value="product" {{ $promotion->type == 'product' ? 'selected' : '' }}>Sản phẩm</option>
                <option value="category" {{ $promotion->type == 'category' ? 'selected' : '' }}>Danh mục</option>
                <option value="all_products" {{ $promotion->type == 'all_products' ? 'selected' : '' }}>Tất cả sản phẩm</option>
                <option value="variant" {{ $promotion->type == 'variant' ? 'selected' : '' }}>Biến thể sản phẩm</option>
            </select>
        </div>

        <div class="form-group">
            <label for="discount_percentage">Phần Trăm Giảm Giá</label>
            <input type="number" name="discount_percentage" id="discount_percentage" class="form-control"
                value="{{ old('discount_percentage', $promotion->discount_percentage) }}" required min="0" max="100">
        </div>

        <div class="form-group">
            <label for="start_date">Ngày Bắt Đầu</label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                value="{{ old('start_date', $promotion->start_date) }}" required>
        </div>

        <div class="form-group">
            <label for="end_date">Ngày Kết Thúc</label>
            <input type="date" name="end_date" id="end_date" class="form-control"
                value="{{ old('end_date', $promotion->end_date) }}" required>
        </div>

        <!-- Thêm phần chọn trạng thái (Active/Inactive) -->
        <div class="form-group">
            <label for="status">Trạng Thái</label>
            <select name="status" id="status" class="form-control">
                <option value="1" {{ $promotion->status == '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ $promotion->status == '0' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>

        <!-- Sản phẩm liên quan -->
        <div id="related-products" class="form-group" style="{{ $promotion->type == 'product' ? '' : 'display: none;' }}">
            <label for="products">Sản Phẩm Liên Quan</label>
            <select name="products[]" id="products" class="form-control" multiple>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"
                        {{ $promotion->products->pluck('id')->contains($product->id) ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Danh mục liên quan -->
        <div id="related-categories" class="form-group" style="{{ $promotion->type == 'category' ? '' : 'display: none;' }}">
            <label for="categories">Danh Mục Liên Quan</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ $promotion->categories->pluck('id')->contains($category->id) ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Biến thể liên quan -->
        <div id="related-variants" class="form-group" style="{{ $promotion->type == 'variant' ? '' : 'display: none;' }}">
            <label for="variants">Biến Thể Liên Quan</label>
            <select name="variants[]" id="variants" class="form-control" multiple>
                @foreach ($variants as $variant)
                    <option value="{{ $variant->id }}"
                        {{ $promotion->variants->pluck('id')->contains($variant->id) ? 'selected' : '' }}>
                        {{ $variant->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập Nhật Khuyến Mãi</button>
    </form>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            var type = this.value;

            document.getElementById('related-products').style.display = 'none';
            document.getElementById('related-categories').style.display = 'none';
            document.getElementById('related-variants').style.display = 'none';

            if (type === 'product') {
                document.getElementById('related-products').style.display = 'block';
            } else if (type === 'category') {
                document.getElementById('related-categories').style.display = 'block';
            } else if (type === 'variant') {
                document.getElementById('related-variants').style.display = 'block';
            }
        });
    </script>
@endsection
