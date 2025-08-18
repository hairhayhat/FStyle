

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

}

/**
 * Xử lý xóa sản phẩm khỏi danh sách yêu thích (wishlist)
 */
function handleRemoveFromWishlist(button) {
    const productId = button.data('product-id');

    // Sử dụng SweetAlert2 thay vì confirm dialog cũ
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            // Người dùng xác nhận xóa - thực hiện AJAX request
            $.ajax({
                url: `/client/products/${productId}/unfavorite`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Content-Type': 'application/json',
                },
                dataType: 'json',
                beforeSend: () => {
                    button.prop('disabled', true).addClass('processing');
                },
                success: (response) => {
                    if (response.status === 'success') {
                        // Xóa row khỏi bảng
                        button.closest('tr').remove();

                        // Kiểm tra nếu không còn sản phẩm nào
                        if ($('tbody tr').length === 0) {
                            location.reload(); // Reload để hiển thị thông báo danh sách trống
                        } else {
                            // Cập nhật số lượng yêu thích trong header nếu có
                            updateWishlistCount(response.favorites_count);
                            showSuccessToast('Đã xóa sản phẩm khỏi danh sách yêu thích');
                        }
                    } else {
                        showErrorToast('Có lỗi xảy ra: ' + response.message);
                    }
                },
                error: (xhr) => {
                    handleError(xhr, button);
                },
                complete: () => {
                    button.prop('disabled', false).removeClass('processing');
                }
            });
        }
    });
}

/**
 * Cập nhật số lượng yêu thích trong header
 */
function updateWishlistCount(count) {
    const wishlistCountElement = $('.wislist-dropdown .label');
    if (wishlistCountElement.length > 0) {
        wishlistCountElement.text(count);
    }
}

/**
 * Hiển thị thông báo lỗi
 */
function showErrorToast(message) {
    Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: message,
        showConfirmButton: false,
        timer: 3000,
        toast: true,
        timerProgressBar: true
    });
}

/**
 * Khởi tạo các event listeners cho wishlist
 */
function initializeWishlist() {
    // Xử lý xóa sản phẩm khỏi danh sách yêu thích
    $(document).on('click', '.remove-favorite', function() {
        handleRemoveFromWishlist($(this));
    });

    // Xử lý mở modal chọn biến thể để thêm vào giỏ hàng
    $(document).on('click', '.add-to-cart-btn', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        openAddToCartModal(productId, productName);
    });

    // Xử lý nút thêm vào giỏ hàng trong modal
    $(document).on('click', '#addToCartBtn', function() {
        addToCartFromWishlist();
    });

    // Xử lý nút heart (favorite) - thêm vào/xóa khỏi danh sách yêu thích
    $(document).on('click', '.heart-wishlist', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const button = $(this);
        const productId = button.data('product-id');
        const isFavorited = button.data('is-favorited') === 'true';

        if (!productId) {
            console.error('Product ID not found for heart button');
            return;
        }

        console.log('Heart button clicked for product:', productId, 'Current state:', isFavorited);

        // Cập nhật trạng thái ngay lập tức để UX tốt hơn
        button.prop('disabled', true);

        // Nếu sản phẩm đã ở trong danh sách yêu thích -> xóa khỏi danh sách
        // Nếu sản phẩm chưa ở trong danh sách yêu thích -> thêm vào danh sách
        const url = isFavorited ? `/client/products/${productId}/unfavorite` : `/client/products/${productId}/favorite`;
        const expectedMessage = isFavorited ? 'Đã xóa sản phẩm khỏi trang yêu thích' : 'Đã thêm sản phẩm vào trang yêu thích';

        // Gọi API để thay đổi trạng thái
        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                console.log('Heart response:', response);

                if (response.status === 'success') {
                    // Cập nhật trạng thái nút
                    const newState = !isFavorited;
                    button.data('is-favorited', newState ? 'true' : 'false');

                    // Cập nhật icon
                    const heartIcon = button.find('i');
                    if (newState) {
                        // Đã thêm vào danh sách yêu thích -> hiển thị trái tim đặc
                        heartIcon.removeClass('far').addClass('fas text-danger');
                    } else {
                        // Đã xóa khỏi danh sách yêu thích -> hiển thị trái tim rỗng
                        heartIcon.removeClass('fas text-danger').addClass('far');
                    }

                    // Cập nhật số lượng yêu thích trong header nếu có
                    if (response.favorites_count !== undefined) {
                        updateWishlistCount(response.favorites_count);
                    }

                    // Hiển thị thông báo tương ứng với hành động
                    const message = response.message || expectedMessage;
                    showSuccessToast(message);
                } else {
                    showErrorToast(response.message || 'Có lỗi xảy ra');
                }
            },
            error: function(xhr) {
                console.error('Heart error:', xhr);

                let errorMessage = 'Có lỗi xảy ra khi thay đổi trạng thái yêu thích';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showErrorToast(errorMessage);
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });
}

/**
 * Khởi tạo event listeners cho tất cả các nút heart trên website
 */
function initializeFavoriteButtons() {
    // Xử lý nút heart (favorite) - thêm vào/xóa khỏi danh sách yêu thích
    $(document).on('click', '.heart-wishlist', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const button = $(this);
        const productId = button.data('product-id');

        if (!productId) {
            console.error('Product ID not found for heart button');
            return;
        }

        console.log('Heart button clicked for product:', productId);
        handleFavoriteAction(button);
    });

    // Xử lý nút favorite-toggle trong sản phẩm
    $(document).on('click', '.favorite-toggle', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const button = $(this);
        const productId = button.data('product-id');
        const isFavorited = button.data('is-favorited') === 'true';

        if (!productId) {
            console.error('Product ID not found for favorite button');
            return;
        }

        console.log('Favorite button clicked for product:', productId, 'Current state:', isFavorited);

        // Cập nhật trạng thái ngay lập tức để UX tốt hơn
        button.prop('disabled', true);

        // Nếu sản phẩm đã ở trong danh sách yêu thích -> xóa khỏi danh sách
        // Nếu sản phẩm chưa ở trong danh sách yêu thích -> thêm vào danh sách
        const url = isFavorited ? `/client/products/${productId}/unfavorite` : `/client/products/${productId}/favorite`;
        const expectedMessage = isFavorited ? 'Đã xóa sản phẩm khỏi trang yêu thích' : 'Đã thêm sản phẩm vào trang yêu thích';

        // Gọi API để thay đổi trạng thái
        $.ajax({
            url: url,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(response) {
                console.log('Favorite response:', response);

                if (response.status === 'success') {
                    // Cập nhật trạng thái nút
                    const newState = !isFavorited;
                    button.data('is-favorited', newState ? 'true' : 'false');

                    // Cập nhật icon
                    const heartIcon = button.find('.heart-icon');
                    if (newState) {
                        // Đã thêm vào danh sách yêu thích -> hiển thị trái tim đặc
                        heartIcon.removeClass('far').addClass('fas text-danger');
                    } else {
                        // Đã xóa khỏi danh sách yêu thích -> hiển thị trái tim rỗng
                        heartIcon.removeClass('fas text-danger').addClass('far');
                    }

                    // Cập nhật số lượng yêu thích trong header nếu có
                    if (response.favorites_count !== undefined) {
                        updateWishlistCount(response.favorites_count);
                    }

                    // Hiển thị thông báo tương ứng với hành động
                    const message = response.message || expectedMessage;
                    showSuccessToast(message);
                } else {
                    showErrorToast(response.message || 'Có lỗi xảy ra');
                }
            },
            error: function(xhr) {
                console.error('Favorite error:', xhr);

                let errorMessage = 'Có lỗi xảy ra khi thay đổi trạng thái yêu thích';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showErrorToast(errorMessage);
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });

    // Xử lý nút remove favorite trong wishlist
    $(document).on('click', '.remove-favorite', function() {
        handleRemoveFromWishlist($(this));
    });
}

// Khởi tạo khi document ready
$(document).ready(function() {
    initializeWishlist();
    initializeFavoriteButtons(); // Khởi tạo các event listeners cho tất cả các nút heart trên website
});

