// resources/js/admin/order-status.js

/**
 * Hàm lấy class màu theo trạng thái
 */
function getStatusClass(status) {
    const statusClasses = {
        'pending': 'btn-warning',
        'confirmed': 'btn-warning',
        'packaging': 'btn-primary',
        'shipped': 'btn-info',
        'delivered': 'btn-success',
        'rated': 'btn-success',
        'cancelled': 'btn-danger',
        'returned': 'btn-dark'
    };
    return statusClasses[status] || 'btn-light';
}

function getStatusVietnameseName(status) {
    const statusNames = {
        'pending': 'Chờ xử lý',
        'confirmed': 'Đã xác nhận',
        'packaging': 'Đang đóng gói',
        'shipped': 'Đang vận chuyển',
        'delivered': 'Đã giao hàng',
        'rated': 'Đã đánh giá',
        'cancelled': 'Đã hủy',
        'returned': 'Trả hàng'
    };
    return statusNames[status] || status;
}

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

/**
 * Xử lý cập nhật trạng thái đơn hàng
 */
function initializeOrderStatus() {
    // Biến lưu trữ select đang mở
    let currentOpenSelect = null;

    // Bắt sự kiện click vào nút hiển thị trạng thái
    $(document).on('click', '.status-display', function (e) {
        e.stopPropagation();

        const $displayBtn = $(this);
        const $select = $displayBtn.next('.status-select');

        // Đóng select đang mở trước đó (nếu có)
        if (currentOpenSelect && !currentOpenSelect.is($select)) {
            const $prevDisplay = currentOpenSelect.prev('.status-display');
            currentOpenSelect.addClass('d-none');
            $prevDisplay.removeClass('d-none');
        }

        // Mở select hiện tại
        $displayBtn.addClass('d-none');
        $select.removeClass('d-none').focus();
        currentOpenSelect = $select;
    });

    // Bắt sự kiện thay đổi trạng thái
    $(document).on('change', '.status-select', function () {
        const $select = $(this);
        const orderId = $select.data('order-id');
        const newStatus = $select.val();
        const $displayBtn = $select.prev('.status-display');

        // Hiển thị loading
        $select.prop('disabled', true);
        // Gửi AJAX request để cập nhật
        $.ajax({
            url: '/admin/order/' + orderId + '/update-status',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { status: newStatus },
            success: function (data) {
                if (data.success) {
                    // Cập nhật giao diện
                    $displayBtn.text(getStatusVietnameseName(newStatus))
                        .removeClass('btn-warning btn-info btn-primary btn-secondary btn-success btn-danger btn-dark btn-light')
                        .addClass(getStatusClass(newStatus));

                    // Ẩn select, hiện lại nút
                    $select.addClass('d-none');
                    $displayBtn.removeClass('d-none');
                    currentOpenSelect = null;

                    // Hiển thị thông báo
                    showSuccessToast('Cập nhật trạng thái thành công');
                } else {
                    showErrorToast(data.message || 'Có lỗi xảy ra');
                    $select.val($displayBtn.text());
                }
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                showErrorToast(response?.message || 'Không thể kết nối tới server');
                $select.val($displayBtn.text());
            },
            complete: function () {
                $select.prop('disabled', false);
            }
        });
    });

    // Bắt sự kiện click ra ngoài để đóng select
    $(document).on('click', function (e) {
        if (currentOpenSelect && !$(e.target).closest('.status-select').length) {
            const $displayBtn = currentOpenSelect.prev('.status-display');
            currentOpenSelect.addClass('d-none');
            $displayBtn.removeClass('d-none');
            currentOpenSelect = null;
        }
    });

    // Bắt sự kiện focusout cho select (phòng trường hợp click ra ngoài không bắt được)
    $(document).on('focusout', '.status-select', function () {
        const $select = $(this);
        // Delay một chút để kiểm tra xem có phải do chọn option không
        setTimeout(() => {
            if (currentOpenSelect && currentOpenSelect.is($select) && !$select.is(':focus')) {
                const $displayBtn = $select.prev('.status-display');
                $select.addClass('d-none');
                $displayBtn.removeClass('d-none');
                currentOpenSelect = null;
            }
        }, 100);
    });
}

// Khởi tạo khi DOM ready
$(document).ready(function () {
    initializeOrderStatus();
});
