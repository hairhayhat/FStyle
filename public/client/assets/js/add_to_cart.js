$(document).ready(function () {
    loadCartDropdown();

    $('#addToCartBtn').on('click', function () {
        const variantId = $('#productVariantId').val();
        const quantity = $('#quantity').val();

        $.ajax({
            url: '/client/add-to-cart',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                product_variant_id: variantId,
                quantity: quantity
            },
            success: function (response) {
                if (response.success) {
                    showSuccessToast(response.message);
                    loadCartDropdown();
                } else {
                    showErrorToast(response.message || 'Thêm vào giỏ hàng thất bại');
                }
            },
            error: function (xhr) {
                showErrorToast('Đã có lỗi xảy ra');
            }
        });
    });

    function loadCartDropdown() {
        $.ajax({
            url: '/client/cart-dropdown',
            method: 'GET',
            success: function (html) {
                $('#cartDropdownContainer').html(html);
            },
            error: function () {
                console.error('Không thể load giỏ hàng');
            }
        });
    }

    $(document).on('click', '.btn-remove-item', function () {
        const itemId = $(this).data('id');

        $.ajax({
            url: '/client/remove-from-cart',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                item_id: itemId
            },
            success: function (response) {
                if (response.success) {
                    showSuccessToast(response.message || 'Đã xoá khỏi giỏ hàng');
                    loadCartDropdown();
                } else {
                    showErrorToast(response.message || 'Không thể xoá sản phẩm');
                }
            },
            error: function () {
                showErrorToast('Đã có lỗi xảy ra khi xoá');
            }
        });
    });

});
