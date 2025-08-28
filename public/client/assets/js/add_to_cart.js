// Định nghĩa hàm thông báo sử dụng SweetAlert2
function showSuccessToast(message) {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        background: '#4CAF50',
        color: 'white'
    });
}

function showErrorToast(message) {
    Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        background: '#f44336',
        color: 'white'
    });
}

$(document).ready(function () {
    // Kiểm tra nếu SweetAlert2 đã được load
    if (typeof Swal === 'undefined') {
        // Fallback sử dụng alert nếu SweetAlert2 không tồn tại
        window.showSuccessToast = function (message) { alert('✓ ' + message); };
        window.showErrorToast = function (message) { alert('✗ ' + message); };
    }

    loadCartDropdown();

    $(document).on('click', '.cart-action-btn', function () {
        const variantId = $('#productVariantId').val() || $(this).data('id');
        const quantity = $('#quantity').val() || $(this).closest('tr').find('.update-quantity').val();
        let action = $(this).data('action');

        if (action === 'add') {
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
                    showErrorToast('Đã có lỗi xảy ra' );
                }
            });
        }

        if (action === 'buy') {
            $.ajax({
                url: '/client/buy-now',
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
                        window.location.href = response.redirect_url;
                    } else {
                        showErrorToast(response.message || 'Mua ngay thất bại');
                    }
                },
                error: function (xhr) {
                    showErrorToast('Đã có lỗi xảy ra')
                }
            });
        }
    });


    function loadCartDropdown() {
        $.ajax({
            url: '/client/cart-dropdown',
            method: 'GET',
            success: function (html) {
                $('#cartDropdownContainer').html(html);
            },
            error: function () {
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

    $(document).on('click', '.remove-item', function () {
        const itemId = $(this).data('id');
        const row = $(this).closest('tr');

        Swal.fire({
            title: 'Bạn có chắc muốn xóa sản phẩm này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
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
                            row.remove();
                            loadCartDropdown();

                            showSuccessToast(response.message || 'Đã xoá khỏi giỏ hàng');
                        } else {
                            showErrorToast(response.message || 'Không thể xoá sản phẩm');
                        }
                    },
                    error: function () {
                        showErrorToast('Đã có lỗi xảy ra khi xoá');
                    }
                });
            }
        });
    });

});
