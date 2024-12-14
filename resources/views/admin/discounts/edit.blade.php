@extends('admin.app')

@section('content')
    <div class="container-fluid">
        <h1>Sửa Mã Giảm Giá</h1>

        <form action="{{ route('admin.discounts.update', $discount->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="code">Mã Giảm Giá</label>
                <input type="text" class="form-control" id="code" name="code"
                    value="{{ old('code', $discount->code) }}" required>
            </div>

            <div class="form-group">
                <label for="discount_type">Loại Giảm Giá</label>
                <select class="form-control" id="discount_type" name="discount_type" required>
                    <option value="percentage" {{ $discount->discount_type == 'percentage' ? 'selected' : '' }}>Phần Trăm
                    </option>
                    <option value="fixed" {{ $discount->discount_type == 'fixed' ? 'selected' : '' }}>Giảm Cố Định</option>
                </select>
            </div>

            <div class="form-group">
                <label for="value">Giá Trị Giảm Giá</label>
                <input type="number" class="form-control" id="value" name="value"
                    value="{{ old('value', $discount->value) }}" required>
            </div>

            <div class="form-group">
                <label for="max_discount_amount">Số Tiền Giảm Tối Đa</label>
                <input type="number" class="form-control" id="max_discount_amount" name="max_discount_amount"
                    value="{{ old('max_discount_amount', $discount->max_discount_amount) }}">
            </div>

            <div class="form-group">
                <label for="min_purchase_amount">Số Tiền Mua Tối Thiểu</label>
                <input type="number" class="form-control" id="min_purchase_amount" name="min_purchase_amount"
                    value="{{ old('min_purchase_amount', $discount->min_purchase_amount) }}">
            </div>

            <div class="form-group">
                <label for="usage_limit">Số Lần Sử Dụng Tối Đa</label>
                <input type="number" class="form-control" id="usage_limit" name="usage_limit"
                    value="{{ old('usage_limit', $discount->usage_limit) }}">
            </div>

            <div class="form-group">
                <label for="usage_per_user">Giới Hạn Sử Dụng Mỗi Người Dùng</label>
                <input type="number" class="form-control" id="usage_per_user" name="usage_per_user"
                    value="{{ old('usage_per_user', $discount->usage_per_user) }}">
            </div>

            <div class="form-group">
                <label for="start_date">Ngày Bắt Đầu</label>
                <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                    value="{{ old('start_date', $discount->start_date ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d\\TH:i') : '') }}">
            </div>

            <div class="form-group">
                <label for="end_date">Ngày Kết Thúc</label>
                <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                    value="{{ old('end_date', $discount->end_date ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d\\TH:i') : '') }}">
            </div>

            <div class="form-group">
                <label for="discount_scope">Phạm Vi Giảm Giá</label>
                <select class="form-control" id="discount_scope" name="discount_scope" required>
                    <option value="user" {{ $discount->discount_scope == 'user' ? 'selected' : '' }}>Người Dùng</option>
                    <option value="product" {{ $discount->discount_scope == 'product' ? 'selected' : '' }}>Sản Phẩm
                    </option>
                    <option value="category" {{ $discount->discount_scope == 'category' ? 'selected' : '' }}>Danh Mục
                    </option>
                    <option value="all" {{ $discount->discount_scope == 'all' ? 'selected' : '' }}>Toàn Bộ</option>
                </select>
            </div>

            <div class="form-group d-none" id="product-section">
                <label for="products">Sản Phẩm </label>
                <select class="form-control" id="products" name="products[]" multiple>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            {{ in_array($product->id, $discount->products->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group d-none" id="category-section">
                <label for="categories">Danh Mục </label>
                <select class="form-control" id="categories" name="categories[]" multiple>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ in_array($category->id, $discount->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group d-none" id="user-section">
                <label for="users">Người Dùng </label>
                <select class="form-control" id="users" name="users[]" multiple>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            {{ in_array($user->id, $discount->users->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="is_active">Kích Hoạt</label>
                <select class="form-control" id="is_active" name="is_active">
                    <option value="1" {{ $discount->is_active == '1' ? 'selected' : '' }}>Có</option>
                    <option value="0" {{ $discount->is_active == '0' ? 'selected' : '' }}>Không</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Lưu Thay Đổi</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const discountScope = document.getElementById('discount_scope');
            const productSection = document.getElementById('product-section');
            const categorySection = document.getElementById('category-section');
            const userSection = document.getElementById('user-section');

            function toggleSections() {
                const scope = discountScope.value;
                productSection.classList.toggle('d-none', scope !== 'product');
                categorySection.classList.toggle('d-none', scope !== 'category');
                userSection.classList.toggle('d-none', scope !== 'user');
            }

            discountScope.addEventListener('change', toggleSections);

            // Initialize on page load
            toggleSections();
        });
    </script>
@endsection
