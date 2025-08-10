$(document).ready(function () {
    $('.update-quantity').on('focus', function () {
        // Lưu giá trị cũ khi bắt đầu sửa
        $(this).data('old-value', $(this).val());
    });

    $('.update-quantity').on('change', function () {
        let url = $(this).data('url');
        let quantity = $(this).val();
        let inputElement = $(this);

        $.ajax({
            url: url,
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { quantity: quantity },
            success: function (data) {
                if (data.success) {
                    inputElement
                        .closest('tr')
                        .find('td:nth-child(6) p')
                        .text(data.itemTotal);

                    $('.cart-checkout-section span').text(data.cartTotal);

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Cập nhật giỏ hàng thành công',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    // Quay lại giá trị cũ
                    inputElement.val(inputElement.data('old-value'));

                    // Hiện thông báo dạng toast góc phải
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: data.message || 'Số lượng sản phẩm không đủ trong kho',
                        showConfirmButton: false,
                        timer: 2000
                    });
                }
            },
            error: function (xhr) {
                // Quay lại giá trị cũ nếu lỗi HTTP
                inputElement.val(inputElement.data('old-value'));

                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: xhr.responseJSON?.message || 'Không thể cập nhật số lượng sản phẩm.',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        });
    });
});
