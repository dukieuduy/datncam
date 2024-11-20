
    <div class="product-detail">
        <h1>{{ $product->name }}</h1>
        <p>{{ $product->description }}</p>

        <p><strong>Danh mục:</strong> {{ $category->name }}</p>
        <p><strong>Số lượng tồn kho:</strong> {{ $stockQuantity }}</p>

        <h3>Chọn màu sắc:</h3>
        <select id="colorSelect">
            @foreach($colors as $color)
                <option value="{{ $color }}">{{ $color }}</option>
            @endforeach
        </select>

        <h3>Chọn kích thước:</h3>
        <select id="sizeSelect">
            <!-- Kích thước sẽ được cập nhật dựa trên màu sắc -->
        </select>

        <h3>Biến thể sản phẩm:</h3>
        <ul>
            @foreach($variations as $variation)
                <li>
                    <p>SKU: {{ $variation->sku }}</p>
                    <p>Giá: {{ number_format($variation->price) }} VNĐ</p>
                    <p>Số lượng: {{ $variation->stock_quantity }}</p>
                    <p><strong>Màu sắc:</strong> 
                    @foreach($variation->variationAttributes as $attribute)
                        @if($attribute->attributeValue->attribute->name == 'Màu sắc')
                            {{ $attribute->attributeValue->value }}<br>
                        @endif
                    @endforeach
                    </p>

                    <p><strong>Kích thước:</strong> 
                    @foreach($variation->variationAttributes as $attribute)
                        @if($attribute->attributeValue->attribute->name == 'Kích thước')
                            {{ $attribute->attributeValue->value }}<br>
                        @endif
                    @endforeach
                    </p>
                </li>
            @endforeach
        </ul>
    </div>
@push('scripts')
    <script>
        document.getElementById('colorSelect').addEventListener('change', function () {
            const selectedColor = this.value;
            const sizeSelect = document.getElementById('sizeSelect');
            
            // Clear the size select
            sizeSelect.innerHTML = '';

            // Fetch the variations that match the selected color
            const matchingSizes = @json($variations)->filter(function (variation) {
                return variation.variationAttributes.some(function (attribute) {
                    return attribute.attributeValue.attribute.name === 'Màu sắc' &&
                           attribute.attributeValue.value === selectedColor;
                });
            }).map(function (variation) {
                return variation.variationAttributes.filter(function (attribute) {
                    return attribute.attributeValue.attribute.name === 'Kích thước';
                }).map(function (attribute) {
                    return attribute.attributeValue.value;
                });
            }).flat();

            // Populate the size select options
            matchingSizes.forEach(function (size) {
                const option = document.createElement('option');
                option.value = size;
                option.textContent = size;
                sizeSelect.appendChild(option);
            });
        });
    </script>
@endpush
