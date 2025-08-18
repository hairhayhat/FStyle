$(document).ready(function () {
    $('#voucher-select').on('change', function () {
        let code = $(this).val();
        let orderAmount = parseFloat($('#cart-total-display').data('total')) || 0;
        $('#voucher_code_input').val(code);

        if (!code) {
            $('#voucher-discount').text('-0đ');
            $('#cart-total-display').text(orderAmount.toLocaleString() + 'đ');
            return;
        }

        $.ajax({
            url: '/client/voucher/check',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                code: code,
                order_amount: orderAmount,
            },
            success: function (res) {
                if (res.success) {
                    $('#voucher-discount').text(`-${res.discount.toLocaleString()}đ`);
                    $('#cart-total-display').text(res.new_total.toLocaleString() + 'đ');

                    // Thông báo thành công
                    showSuccessToast('Áp dụng voucher thành công!');
                } else {
                    // Thông báo lỗi
                    showErrorToast(res.message);

                    $('#voucher-discount').text('-0đ');
                    $('#cart-total-display').text(orderAmount.toLocaleString() + 'đ');
                }
            }
        });
    });
});

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
