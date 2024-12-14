$(document).ready(function () {
    $('#checkout').on('click', function () {
        let address = $('input[name="address"]:checked').val();
        let paymentMethod = $('input[name="payment_method"]:checked').val();
        let totalAmount = $('.total-amount').text();
        let products = [];

        $('.cart_item_id').each(function(index) {
            let product = {
                id: $(this).text().trim(),
                quantity: $('.quantity-input').eq(index).val().trim(),
                price: $('.price').eq(index).text().trim(),
                subtotal: $('.subtotal').eq(index).text().trim()
            };

            products.push(product);
        });

        console.log(products)

        $('#error_address').text('');
        $('#error_payment').text('');

        let isValid = true;

        if (!address) {
            $('#error_address').text('Vui lòng chọn 1 địa chỉ nhận hàng !');
            isValid = false;
        }

        if (!paymentMethod) {
            $('#error_payment').text('Vui lòng chọn 1 phương thức thanh toán !');
            isValid = false;
        }

        if (products.length === 0) {
            $('#error_cart_items').text('Vui lòng chọn ít nhất một sản phẩm !');
            isValid = false;
        }

        if (isValid) {
            let data = {
                shipping_address: address,
                payment_method: paymentMethod,
                total_amount: totalAmount,
                products: products
            };

            console.log(data);

            $.ajax({
                url: $('#checkout').attr('data-url'),
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (response) {
                    alert('Thanh toán thành công, vui lòng kiểm tra đơn hàng của bạn trong phần "Đơn hàng của tôi"')
                    window.location.href = '/'
                },
                error: function (err) {
                    console.log('Error occurred: ' + err);
                }
            });
        }
    });
});
