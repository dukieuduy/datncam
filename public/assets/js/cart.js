
document.getElementById('select-all').addEventListener('change', function () {
    const isChecked = this.checked;
    const checkboxes = document.querySelectorAll('.item-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });
});

document.querySelectorAll('.item-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const allChecked = Array.from(document.querySelectorAll('.item-checkbox'))
            .every(checkbox => checkbox.checked);
        document.getElementById('select-all').checked = allChecked;
    });
});

function updateCart(input) {
    const quantity = parseInt(input.value);
    const price = parseInt(input.dataset.price);
    const total = quantity * price;

    const totalElement = input.closest('tr').querySelector('.product_total span');
    totalElement.textContent = total.toLocaleString('vi-VN');

    updateCartTotal();
}

function updateCartTotal() {
    let totalAmount = 0;

    document.querySelectorAll('.quantity-input').forEach(input => {
        const quantity = parseInt(input.value);
        const price = parseInt(input.dataset.price);
        totalAmount += quantity * price;
    });

    const shippingFee = 55000;

    const totalAmountElement = document.querySelector('.cart_amount.total');
    totalAmountElement.textContent = totalAmount.toLocaleString('vi-VN') + 'đ';

    const totalWithShippingElement = document.querySelector('.total-amount');
    totalWithShippingElement.textContent = (totalAmount + shippingFee).toLocaleString('vi-VN') + 'đ';
}


