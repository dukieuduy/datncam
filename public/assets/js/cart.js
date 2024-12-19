// Select All Checkbox
document.getElementById('select-all').addEventListener('change', function () {
    const isChecked = this.checked;
    const checkboxes = document.querySelectorAll('.item-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
    });

    // Update the total when select-all is changed
    updateCartTotal();
});

// Individual item checkboxes
document.querySelectorAll('.item-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const allChecked = Array.from(document.querySelectorAll('.item-checkbox'))
            .every(checkbox => checkbox.checked);
        document.getElementById('select-all').checked = allChecked;

        // Update the total when any individual item is checked or unchecked
        updateCartTotal();
    });
});

// Update total for a single item
function updateCart(input) {
    const quantity = parseInt(input.value);
    const price = parseInt(input.dataset.price);
    const total = quantity * price;

    // Update the product's total in the table
    const totalElement = input.closest('tr').querySelector('.product_total span');
    totalElement.textContent = total.toLocaleString('vi-VN');

    // Update the total cart value based on selected items
    updateCartTotal();
}

// Update the total cart value based on selected items
function updateCartTotal() {
    let totalAmount = 0;

    // Loop through all items
    document.querySelectorAll('.quantity-input').forEach(input => {
        const quantity = parseInt(input.value);
        const price = parseInt(input.dataset.price);

        // Check if the item is selected
        const checkbox = input.closest('tr').querySelector('.item-checkbox');
        if (checkbox.checked) {
            totalAmount += quantity * price; // Add to total if selected
        }
    });

    // Shipping fee (fixed)
    const shippingFee = 55000;

    // Update the total amount
    const totalAmountElement = document.querySelector('.cart_amount.total');
    totalAmountElement.textContent = totalAmount.toLocaleString('vi-VN') + 'đ';

    // Update total amount with shipping
    const totalWithShippingElement = document.querySelector('.total-amount');
    totalWithShippingElement.textContent = (totalAmount + shippingFee).toLocaleString('vi-VN') + 'đ';
}
