$(document).ready(function () {
    $('.btn-receive-order').click(function () {
        const orderId = $(this).data('order-id');

        Swal.fire({
            title: 'Bạn đã nhận đơn?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Gửi AJAX cập nhật trạng thái
                $.ajax({
                    url: `/client/order/${orderId}/update-status`,
                    type: 'POST',
                    data: JSON.stringify({ status: 'delivered' }),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Đã nhận đơn thành công!',
                                text: 'Mời bạn đánh giá sản phẩm ngay!',
                                icon: 'success',
                                showDenyButton: true,
                                confirmButtonText: 'Đánh giá',
                                denyButtonText: 'Để sau',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire("Saved!", "", "success");
                                } else if (result.isDenied) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire('Lỗi', 'Cập nhật trạng thái thất bại. Vui lòng thử lại.', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
                    }
                });
            }
        });
    });
});

$('.btn-cancel-order').click(function () {
    const orderId = $(this).data('order-id');

    Swal.fire({
        title: 'Hủy đơn hàng',
        html: `
            <p>Chọn lý do hủy hoặc nhập lý do khác:</p>
            <div style="text-align:left; margin-bottom:10px;">
                <label><input type="radio" name="cancelReason" value="Không còn nhu cầu" /> Không còn nhu cầu</label><br>
                <label><input type="radio" name="cancelReason" value="Giá cao" /> Giá cao</label><br>
                <label><input type="radio" name="cancelReason" value="Giao hàng chậm" /> Giao hàng chậm</label><br>
                <label><input type="radio" name="cancelReason" value="Khác" id="reasonOtherRadio" /> Khác</label>
            </div>
            <textarea id="customReason" placeholder="Nhập lý do khác..." style="width:100%; display:none;"></textarea>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Xác nhận hủy',
        cancelButtonText: 'Hủy bỏ',
        preConfirm: () => {
            let selectedReason = $('input[name="cancelReason"]:checked').val();
            let customReason = $('#customReason').val().trim();

            if (!selectedReason) {
                Swal.showValidationMessage('Vui lòng chọn hoặc nhập lý do hủy');
                return false;
            }

            if (selectedReason === 'Khác' && customReason === '') {
                Swal.showValidationMessage('Vui lòng nhập lý do khác');
                return false;
            }

            return selectedReason === 'Khác' ? customReason : selectedReason;
        },
        didOpen: () => {
            $('input[name="cancelReason"]').change(function () {
                if ($('#reasonOtherRadio').is(':checked')) {
                    $('#customReason').show().focus();
                } else {
                    $('#customReason').hide();
                }
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const reason = result.value;
            $.ajax({
                url: `/client/order/${orderId}/cancel`,
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ reason: reason, status: 'cancelled' }),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    if (res.success) {
                        Swal.fire('Đã hủy đơn', 'Đơn hàng của bạn đã được hủy.', 'success');
                        window.location.reload();
                    } else {
                        Swal.fire('Lỗi', 'Hủy đơn thất bại, vui lòng thử lại.', 'error');
                    }
                },
                error: function () {
                    Swal.fire('Lỗi', 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
                }
            });
        }
    });
});

