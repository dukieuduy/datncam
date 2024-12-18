$(document).ready(function () {
    let originalTotal = parseFloat($('.total-amount').text().replace(/\./g, '').replace(' đ', ''));
    let total = $('.total').text().replace(/\./g, '').replace(' đ', '');

    $('#discount-options').on('change', function() {
        var selectedOptionValue = $(this).val().replace(/\./g, '').replace(' đ', '');

        $.ajax({
            url: '/get-data-discount/' + selectedOptionValue,
            method: 'GET',
            success: function(response) {
                let minAmount = Math.floor(response.data.min_purchase_amount);
                if (total < minAmount) {
                    alert(`Không áp dụng cho đơn hàng có tổng tiền nhỏ hơn ${minAmount} đ`);
                    return;
                }

                let discountAmount = 0;
                let totalAfterApplyDiscount = originalTotal;

                if (response.data.type === 'percentage') {
                    discountAmount = originalTotal * response.data.value / 100;
                    if (response.data.max_purchase_amount !== null) {
                        let maxPurchaseAmount = parseFloat(response.data.max_purchase_amount.replace(/[^0-9.-]+/g, ''));
                        if (discountAmount > maxPurchaseAmount) {
                            discountAmount = maxPurchaseAmount
                        }
                    }
                    totalAfterApplyDiscount -= discountAmount;
                } else if (response.data.type === 'fixed') {
                    discountAmount = parseFloat(response.data.value);
                    totalAfterApplyDiscount -= discountAmount;
                } else if (response.data.type === 'shipping') {
                    let shippingFee = parseFloat($('.shipping-fee').data('shipping'));
                    let newShippingFee = shippingFee - discountAmount;
                    totalAfterApplyDiscount = originalTotal + newShippingFee;
                }

                totalAfterApplyDiscount = totalAfterApplyDiscount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                $('.total-amount').text(totalAfterApplyDiscount);
                $('#discount_total').text(discountAmount + ' đ');

                alert(`Áp mã giảm giá thành công, giảm ${discountAmount} đ`);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $('#checkout').on('click', function () {
        let address = $('input[name="address"]:checked').val();
        let paymentMethod = $('input[name="payment_method"]:checked').val();
        let totalAmount = $('.total-amount').text().replace('.', '');;
        let discount = $('#discount-options').val();
        let discount_total = $('#discount_total').text().replace(' đ', '');;
        let products = [];

        $('.cart_item_id').each(function(index) {
            let product = {
                id: $(this).text().trim(),
                quantity: $('.quantity-input').eq(index).val().trim(),
                price: $('.price').eq(index).text().trim().replace('.', ''),
                subtotal: $('.subtotal').eq(index).text().trim().replace('.', '')
            };
            products.push(product)
        });


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
                products: products,
                discount : discount,
                discount_total : discount_total
            };

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
