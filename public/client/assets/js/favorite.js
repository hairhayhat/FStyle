function handleFavoriteAction(button) {
    const productId = button.data('product-id');
    const isFavorited = button.data('is-favorited') === 'true';
    console.log('Product ID:', productId, 'Is Favorited:', isFavorited);

    const { url, successMessage } = getFavoriteUrls(productId, isFavorited);
    console.log('AJAX URL:', url);

    $.ajax({
        url: url,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        beforeSend: () => {
            button.prop('disabled', true).addClass('processing');
        },
        success: (response) => {
            console.log('Success response:', response);
            handleSuccess(response, button, isFavorited, successMessage);
        },
        error: (xhr) => {
            console.error('Error:', xhr.responseText);
            handleError(xhr, button);
        },
        complete: () => {
            button.prop('disabled', false).removeClass('processing');
        }
    })
}

function getFavoriteUrls(productId, isFavorited) {
    return isFavorited
        ? {
            url: `/client/products/${productId}/unfavorite`,
            successMessage: 'Đã xóa khỏi danh sách yêu thích'
        }
        : {
            url: `/client/products/${productId}/favorite`,
            successMessage: 'Đã thêm vào danh sách yêu thích'
        };
}

/**
 * Xử lý khi request thành công
 */
function handleSuccess(response, button, isFavorited, successMessage) {
    // Cập nhật trạng thái nút
    updateButtonState(button, !isFavorited);

    // Cập nhật biểu tượng trái tim
    updateHeartIcon(button.find('.heart-icon'), isFavorited);

    // Cập nhật số lượng yêu thích
    updateFavoritesCount(button.find('.favorites-count'), response.favorites_count);

    // Hiển thị thông báo
    showSuccessToast(successMessage);

    // Log kết quả
    console.log('Thành công:', response);
}

/**
 * Cập nhật trạng thái nút
 */
function updateButtonState(button, newState) {
    button.data('is-favorited', newState);
}

/**
 * Cập nhật biểu tượng trái tim
 */
function updateHeartIcon(icon, isFavorited) {
    icon.toggleClass('fas text-danger far', !isFavorited);
}

/**
 * Cập nhật số lượng yêu thích
 */
function updateFavoritesCount(element, count) {
    element.text(count);
}

/**
 * Hiển thị thông báo thành công
 */
function showSuccessToast(message) {
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: message,
        showConfirmButton: false,
        timer: 1500,
        toast: true,
        timerProgressBar: true
    });
}

/**
 * Xử lý khi request thất bại
 */
function handleError(xhr, button) {
    let errorMessage = 'Đã có lỗi xảy ra. Vui lòng thử lại!';
    let needReload = false;

    // Xử lý các loại lỗi khác nhau
    switch (xhr.status) {
        case 401:
            errorMessage = 'Bạn cần đăng nhập để thực hiện chức năng này';
            needReload = true;
            break;
        case 419:
            errorMessage = 'Phiên làm việc hết hạn. Vui lòng tải lại trang';
            needReload = true;
            break;
        case 422:
            errorMessage = 'Dữ liệu không hợp lệ';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                errorMessage = Object.values(xhr.responseJSON.errors).join('<br>');
            }
            break;
        case 404:
            errorMessage = 'Không tìm thấy sản phẩm';
            break;
        case 500:
            errorMessage = 'Lỗi hệ thống. Vui lòng thử lại sau';
            break;
    }

    // Hiển thị thông báo lỗi
    const swalConfig = {
        icon: 'error',
        title: 'Lỗi',
        html: errorMessage,
        confirmButtonText: 'Đóng'
    };

    if (needReload) {
        swalConfig.willClose = () => location.reload();
    }

    Swal.fire(swalConfig);

    // Log lỗi chi tiết
    console.error('Lỗi AJAX:', {
        status: xhr.status,
        response: xhr.responseJSON || xhr.responseText,
        button: button
    });
}

