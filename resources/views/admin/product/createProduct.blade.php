@extends("admin.app")
@section("content")
{{-- <div class="container mt-5">
    <h2 class="mb-4">Thêm Sản Phẩm</h2>
    <form>
        <!-- Tên sản phẩm -->
        <div class="mb-3">
            <label for="productName" class="form-label">Tên Sản Phẩm</label>
            <input type="text" class="form-control" id="productName" name="productName" placeholder="Nhập tên sản phẩm" >
        </div>

        <!-- Mô tả sản phẩm -->
        <div class="mb-3">
            <label for="productDescription" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="productDescription" name="productDescription" rows="3" placeholder="Nhập mô tả sản phẩm" ></textarea>
        </div>

        <!-- Danh mục -->
        <div class="mb-3">
            <label for="productCategory" class="form-label">Danh Mục</label>
            <select class="form-select" id="productCategory" name="productCategory" required>
                <option selected disabled>Chọn danh mục</option>
                <option value="electronics">Điện tử</option>
                <option value="fashion">Thời trang</option>
                <option value="home">Đồ gia dụng</option>
                <option value="other">Khác</option>
            </select>
        </div>

        <!-- Biến thể -->
        <div id="variantsContainer">
            <h4 class="mt-4">Biến Thể</h4>
            <div class="variant-group row border rounded p-3 mb-3">
                <div class="col-md-4 mb-3">
                    <label for="variantColor" class="form-label">Màu Sắc</label>
                    <input type="text" class="form-control" name="variantColor[]" placeholder="Ví dụ: Đỏ" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="variantSize" class="form-label">Kích Thước</label>
                    <input type="text" class="form-control" name="variantSize[]" placeholder="Ví dụ: M, L, XL" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="variantPrice" class="form-label">Giá</label>
                    <input type="number" class="form-control" name="variantPrice[]" placeholder="Ví dụ: 500000" required>
                </div>
            </div>
        </div>

        <!-- Nút thêm biến thể -->
        <button type="button" class="btn btn-secondary" id="addVariantButton">Thêm Biến Thể</button>

        <!-- Nút submit -->
        <button type="submit" class="btn btn-primary">Lưu Sản Phẩm</button>
    </form>
</div>

<!-- Link Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('addVariantButton').addEventListener('click', function () {
        const variantGroup = `
            <div class="variant-group row border rounded p-3 mb-3">
                <div class="col-md-4 mb-3">
                    <label for="variantColor" class="form-label">Màu Sắc</label>
                    <input type="text" class="form-control" name="variantColor[]" placeholder="Ví dụ: Đỏ" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="variantSize" class="form-label">Kích Thước</label>
                    <input type="text" class="form-control" name="variantSize[]" placeholder="Ví dụ: M, L, XL" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="variantPrice" class="form-label">Giá</label>
                    <input type="number" class="form-control" name="variantPrice[]" placeholder="Ví dụ: 500000" required>
                </div>
            </div>`;
        document.getElementById('variantsContainer').insertAdjacentHTML('beforeend', variantGroup);
    });
</script> --}}
<div class="container">
    <h1>Thêm Sản Phẩm</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Tên sản phẩm -->
        <div class="form-group">
            <label for="name">Tên Sản Phẩm</label>
            <input type="text" class="form-control" name="name" id="name" >
            @if ($errors->has('name'))
            <div class="alert alert-danger">
                {{ $errors->first('name') }}
            </div>
        @endif
        </div>

        <!-- Mô tả sản phẩm -->
        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea class="form-control" name="description" id="description" ></textarea>
            @if ($errors->has('name'))
            <div class="alert alert-danger">
                {{ $errors->first('name') }}
            </div>
        @endif
        </div>

    


        <div class="form-group">
            <label>Danh Mục Sản Phẩm</label>
            <select name="category" class="form-control">
                @foreach($category as $value)
                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Chọn thuộc tính cho sản phẩm (Kích thước, Màu sắc) -->

        <!-- Thêm Biến Thể -->
        <h3>Biến Thể Sản Phẩm</h3>
        <div id="variations">
            <div class="variation">
                <div class="form-group">
                    <label for="sku">SKU</label>
                    <input type="text" name="variations[0][sku]" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="variation_price">Giá Biến Thể</label>
                    <input type="number" name="variations[0][price]" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="stock_quantity">Số Lượng</label>
                    <input type="number" name="variations[0][stock_quantity]" class="form-control" >
                </div>

                <div class="form-group">
                    <label for="image">Ảnh</label>
                    <input type="file" name="variations[0][image]" class="form-control" >
                </div>
                <!-- Chọn thuộc tính cho biến thể -->
                @foreach($attributes as $attribute)
                    <div class="form-group">
                        <label>{{ $attribute->name }}</label>
                        <select name="variations[0][attributes][{{ $attribute->id }}]" class="form-control" accept="image/*">
                            @foreach($attribute->values as $value)
                                <option value="{{ $value->id }}">{{ $value->value }}</option>
                            @endforeach
                        </select>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="button" id="addVariation" class="btn btn-primary mt-3">Thêm Biến Thể</button>

        <button type="submit" class="btn btn-success mt-3">Lưu Sản Phẩm</button>
    </form>
</div>

<script>
    let variationIndex = 1;
    document.getElementById('addVariation').addEventListener('click', function() {
        let variationHTML = document.querySelector('.variation').outerHTML;
        variationHTML = variationHTML.replace(/\[0\]/g, '[' + variationIndex + ']');
        document.getElementById('variations').insertAdjacentHTML('beforeend', variationHTML);
        variationIndex++;
    });
</script>
@endsection