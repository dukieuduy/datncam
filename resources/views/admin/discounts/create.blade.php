@extends('admin.app')

@section('content')
    <div class="container-fluid">
        <h1>Tạo Mã Giảm Giá Mới</h1>

        <form action="{{ route('admin.discounts.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="code">Mã Giảm Giá</label>
                <input type="text" name="code" id="code" class="form-control">
                @error('code')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="discount_type">Loại Giảm Giá</label>
                <select name="discount_type" id="discount_type" class="form-control">
                    <option value="percentage">Phần Trăm</option>
                    <option value="fixed">Giảm Cố Định</option>
                </select>
                @error('discount_type')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="value">Giá Trị Giảm Giá</label>
                <input type="number" name="value" id="value" class="form-control" min="0" max="100">
                @error('value')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="max_discount_amount">Số Tiền Giảm Tối Đa</label>
                <input type="number" name="max_discount_amount" id="max_discount_amount" class="form-control">
                @error('max_discount_amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="min_purchase_amount">Số Tiền Mua Tối Thiểu</label>
                <input type="number" name="min_purchase_amount" id="min_purchase_amount" class="form-control">
                @error('min_purchase_amount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="usage_limit">Số Lần Sử Dụng Tối Đa</label>
                <input type="number" name="usage_limit" id="usage_limit" class="form-control">
                @error('usage_limit')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="usage_per_user">Giới Hạn Sử Dụng Cho Mỗi Người Dùng</label>
                <input type="number" name="usage_per_user" id="usage_per_user" class="form-control">
                @error('usage_per_user')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_date">Ngày Bắt Đầu</label>
                <input type="datetime-local" name="start_date" id="start_date" class="form-control">
                @error('start_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_date">Ngày Kết Thúc</label>
                <input type="datetime-local" name="end_date" id="end_date" class="form-control">
                @error('end_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="discount_scope">Phạm Vi Giảm Giá</label>
                <select name="discount_scope" id="discount_scope" class="form-control">
                    <option value="all">Toàn Bộ</option>
                    <option value="product">Sản Phẩm</option>
                    <option value="category">Danh Mục</option>
                    <option value="user">Người Dùng</option>
                </select>
            </div>

            <!-- Phần sản phẩm liên quan (ẩn ban đầu) -->
            <div id="related-products" class="form-group" style="display: none;">
                <label for="products">Sản Phẩm Liên Quan</label>
                <select name="products[]" id="products" class="form-control" multiple>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Phần danh mục liên quan (ẩn ban đầu) -->
            <div id="related-categories" class="form-group" style="display: none;">
                <label for="categories">Danh Mục Liên Quan</label>
                <select name="categories[]" id="categories" class="form-control" multiple>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Phần người dùng liên quan (ẩn ban đầu) -->
            <div id="related-users" class="form-group" style="display: none;">
                <label for="users">Người Dùng Liên Quan</label>
                <select name="users[]" id="users" class="form-control" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="is_active">Kích Hoạt</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1">Kích Hoạt</option>
                    <option value="0">Không Kích Hoạt</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Tạo Mã Giảm Giá</button>
        </form>

        <script>
            // Lắng nghe sự thay đổi của dropdown 'Phạm Vi Giảm Giá'
            document.getElementById('discount_scope').addEventListener('change', function() {
                var scope = this.value;

                // Ẩn tất cả các phần liên quan
                document.getElementById('related-products').style.display = 'none';
                document.getElementById('related-categories').style.display = 'none';
                document.getElementById('related-users').style.display = 'none';

                // Hiển thị các phần liên quan tương ứng với phạm vi đã chọn
                if (scope === 'product') {
                    document.getElementById('related-products').style.display = 'block';
                } else if (scope === 'category') {
                    document.getElementById('related-categories').style.display = 'block';
                } else if (scope === 'user') {
                    document.getElementById('related-users').style.display = 'block';
                }
            });
        </script>
    </div>
@endsection
