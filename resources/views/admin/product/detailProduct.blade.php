@extends("admin.app")

@section("content")
<div class="container mt-4">
    <h1>{{ $product->name }}</h1>
    <p>{{ $product->description }}</p>
    <p><strong>Danh mục:</strong> {{ $product->category->name }}</p>

    <h3>Biến thể sản phẩm:</h3>
    <!-- Nút "Thêm Biến Thể" -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVariationModal">Thêm Biến Thể</button>

    <!-- Modal Form Thêm Biến Thể -->
    <div class="modal fade" id="addVariationModal" tabindex="-1" aria-labelledby="addVariationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVariationModalLabel">Thêm Biến Thể</h5>
                </div>
                <div class="modal-body">
                    <form id="addVariationForm">
                        @csrf
                        <div class="mb-3">
                            <label for="size" class="form-label">Size</label>
                            <select class="form-select" name="size" id="size">
                                <option value="">chọn size</option>
                                <!-- Lặp qua các size và tạo các option -->
                                @foreach($sizes as $size)
                                    <option value="{{ $size->value }}">{{ $size->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="color" class="form-label">Color</label>
                            <select class="form-select" name="color" id="color">
                                <option value="">chọn màu</option>

                                <!-- Lặp qua các color và tạo các option -->
                                @foreach($colors as $color)
                                    <option value="{{ $color->value }}">{{ $color->value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Số lượng</label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm Biến Thể</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <table border="1" cellpadding="10" class="table">
        <thead>
            <tr>
                <th>Mã SKU</th>
                <th>Giá</th>
                <th>Ảnh</th>
                <th>Số lượng</th>
                <th>Thuộc tính</th>
            </tr>
        </thead>
        <tbody>
            @foreach($product->variations as $variation)
                <tr>
                    <td>{{ $variation->sku }}</td>
                    <td>{{ $variation->price }}</td>
                    <td>
                        @if($variation->image)
                            <img src="{{ asset('storage/' . $variation->image) }}" alt="{{ $product['name'] }}" width="50">
                        @else
                            <span>Không có ảnh</span>
                        @endif
                    </td>
                    <td>{{ $variation->stock_quantity }}</td>
                    <td>
                        <ul>
                            @foreach($variation->variationAttributes as $variationAttribute)
                                <li>
                                    <strong>{{ $variationAttribute->attributeValue->attribute->name }}:</strong>
                                    {{ $variationAttribute->attributeValue->value }}
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Mở modal khi bấm vào nút
        $('[data-bs-toggle="modal"]').on('click', function() {
            var targetModal = $(this).data('bs-target');
            var modal = new bootstrap.Modal(document.querySelector(targetModal));
            modal.show();
        });
    });
</script>
{{-- <script>
    $(document).ready(function() {
        // Dữ liệu các biến thể hiện có
        var existingVariations = @json($existingVariations);

        // Hàm kiểm tra nếu kết hợp size và color đã tồn tại trong biến thể
        function checkExistingVariation(size, color) {
            for (var i = 0; i < existingVariations.length; i++) {
                if (existingVariations[i].size == size && existingVariations[i].color == color) {
                    return true; // Kết hợp size và color đã tồn tại
                }
            }
            return false; // Kết hợp size và color chưa tồn tại
        }

        // Lắng nghe sự kiện thay đổi của size và color
        $('#size, #color').on('change', function() {
            var selectedSize = $('#size').val();
            var selectedColor = $('#color').val();

            // Kiểm tra nếu kết hợp size và color đã tồn tại
            if (selectedSize && selectedColor) {
                if (checkExistingVariation(selectedSize, selectedColor)) {
                    alert('Biến thể với size ' + selectedSize + ' và color ' + selectedColor + ' đã tồn tại!');
                }
            }
        });

        // Khi form thêm biến thể được submit
        $('#addVariationForm').submit(function(e) {
            e.preventDefault();  // Ngăn chặn việc submit form mặc định

            var formData = new FormData(this);  // Lấy tất cả dữ liệu trong form

            // Lấy CSRF token từ meta tag để gửi trong header
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Gửi yêu cầu AJAX để thêm biến thể
            $.ajax({
                url: '/product/{{ $product->id }}/variations',  // Đường dẫn API để thêm biến thể
                type: 'POST',
                data: formData,
                processData: false,  // Không xử lý dữ liệu
                contentType: false,  // Không cần chỉ định content type
                headers: {
                    'X-CSRF-TOKEN': csrfToken  // Gửi CSRF token trong header
                },
                success: function(response) {
                    if (response.success) {
                        // Đóng modal
                        $('#addVariationModal').modal('hide');
                        
                        // Hiển thị thông báo thành công
                        alert('Thêm biến thể thành công!');
                        
                        // Cập nhật danh sách các biến thể hiện có trong frontend
                        existingVariations.push(response.data); // Thêm biến thể mới vào danh sách
                        
                        // Cập nhật lại dropdown size và color sau khi thêm biến thể
                        $('#size option').each(function() {
                            if (checkExistingVariation($(this).val(), $('#color').val())) {
                                $(this).prop('disabled', true);
                            } else {
                                $(this).prop('disabled', false);
                            }
                        });

                        $('#color option').each(function() {
                            if (checkExistingVariation($('#size').val(), $(this).val())) {
                                $(this).prop('disabled', true);
                            } else {
                                $(this).prop('disabled', false);
                            }
                        });

                        // Cập nhật bảng biến thể mà không tải lại trang
                        $('table tbody').append(
                            `<tr>
                                <td>${response.data.sku}</td>
                                <td>${response.data.price}</td>
                                <td><img src="${response.data.image}" width="50"></td>
                                <td>${response.data.stock_quantity}</td>
                                <td>${response.data.size} - ${response.data.color}</td>
                            </tr>`
                        );
                    } else {
                        alert('Có lỗi khi thêm biến thể!');
                    }
                },
                error: function(xhr, status, error) {
                    alert('Có lỗi khi gửi yêu cầu!');
                }
            });
        });
    });
</script> --}}
@endsection
