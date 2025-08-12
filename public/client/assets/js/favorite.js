function handleFavoriteAction(button) {
    const productId = button.data('product-id');
    const rawState = button.data('is-favorited');
    const isFavorited = (rawState === true || rawState === 'true');
 

    const { url, successMessage } = getFavoriteUrls(productId, isFavorited);
   

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
           
            // Ưu tiên dùng trạng thái từ server nếu có
            const serverState = typeof response.is_favorited === 'boolean' ? response.is_favorited : !isFavorited;
            handleSuccessWithState(response, button, serverState, successMessage);
        },
        error: (xhr) => {
           
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
function handleSuccessWithState(response, button, isFavoritedState, successMessage) {
    // Cập nhật trạng thái nút theo state từ server
    updateButtonState(button, isFavoritedState);

    // Cập nhật biểu tượng trái tim
    updateHeartIcon(button.find('.heart-icon'), isFavoritedState);

    // Cập nhật số lượng yêu thích
    updateFavoritesCount(button.find('.favorites-count'), response.favorites_count);

    // Thông báo
    showSuccessToast(successMessage || response.message || 'Thành công');


}

/**
 * Cập nhật trạng thái nút
 */
function updateButtonState(button, newState) {
    const str = newState ? 'true' : 'false';
    button.attr('data-is-favorited', str);
    button.data('is-favorited', str);
}

/**
 * Cập nhật biểu tượng trái tim
 */
function updateHeartIcon(icon, isFavorited) {
    if (isFavorited) {
        // Nếu đã yêu thích -> hiển thị trái tim đặc (fas fa-heart text-danger)
        icon.removeClass('far').addClass('fas text-danger');
    } else {
        // Nếu chưa yêu thích -> hiển thị trái tim rỗng (far fa-heart)
        icon.removeClass('fas text-danger').addClass('far');
    }
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
   
}

/**
 * Xử lý xóa sản phẩm khỏi danh sách yêu thích (wishlist)
 */
function handleRemoveFromWishlist(button) {
    const productId = button.data('product-id');
    
    if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
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
}

/**
 * Mở modal chọn biến thể sản phẩm
 */
function openAddToCartModal(productId, productName) {
    // Reset modal
    resetModal();
    
    // Hiển thị tên sản phẩm và lưu product ID
    $('#modalProductName').text(productName).data('product-id', productId);
    
    // Lấy thông tin biến thể
    loadProductVariants(productId);
    
    // Hiển thị modal
    $('#productVariantsModal').modal('show');
}

/**
 * Reset modal về trạng thái ban đầu
 */
function resetModal() {
    $('#colorSelect').val('').prop('disabled', true);
    $('#sizeSelect').val('').prop('disabled', true);
    $('#quantityInput').val(1);
    $('#selectedVariantInfo').hide();
    $('#quantitySelection').hide();
    $('#variantsTable').hide();
    $('#addToCartBtn').prop('disabled', true);
    
    // Reset table
    $('#variantsTableBody').empty();
}

/**
 * Load danh sách biến thể của sản phẩm
 */
function loadProductVariants(productId) {
    $.ajax({
        url: `/client/products/${productId}/variants`,
        method: 'GET',
        dataType: 'json',
        beforeSend: function() {
            // Hiển thị loading
            $('#modalProductName').html('<i class="fas fa-spinner fa-spin"></i> Đang tải...');
        },
        success: function(response) {
            if (response.status === 'success') {
                displayProductVariants(response.data);
            } else {
                showErrorToast('Không thể tải thông tin biến thể');
            }
        },
        error: function(xhr) {
            let errorMessage = 'Không thể tải thông tin biến thể';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showErrorToast(errorMessage);
        },
        complete: function() {
            // Ẩn loading
            $('#modalProductName').text(response.data.product.name);
        }
    });
}

/**
 * Hiển thị danh sách biến thể trong modal
 */
function displayProductVariants(data) {
    const { product, variants, colors, sizes } = data;
    
    // Hiển thị hình ảnh sản phẩm
    if (product.image) {
        $('#modalProductImage').attr('src', `/storage/${product.image}`);
    } else {
        $('#modalProductImage').attr('src', '/client/assets/assets/images/fashion/product/front/1.jpg');
    }
    
    // Populate color select
    populateSelect('#colorSelect', colors, 'name');
    
    // Populate size select
    populateSelect('#sizeSelect', sizes, 'name');
    
    // Hiển thị bảng biến thể
    displayVariantsTable(variants);
    
    // Enable selects
    $('#colorSelect, #sizeSelect').prop('disabled', false);
    
    // Bind events
    bindVariantSelectionEvents(variants);
}

/**
 * Populate select dropdown
 */
function populateSelect(selector, items, displayField) {
    const select = $(selector);
    select.find('option:not(:first)').remove();
    
    items.forEach(item => {
        select.append(`<option value="${item.id}">${item[displayField]}</option>`);
    });
}

/**
 * Hiển thị bảng biến thể
 */
function displayVariantsTable(variants) {
    const tbody = $('#variantsTableBody');
    tbody.empty();
    
    variants.forEach(variant => {
        const row = `
            <tr data-variant-id="${variant.id}" 
                data-color-id="${variant.color_id}" 
                data-size-id="${variant.size_id}"
                data-price="${variant.price}"
                data-quantity="${variant.quantity}">
                <td>${variant.color_name}</td>
                <td>${variant.size_name}</td>
                <td>${variant.formatted_price}</td>
                <td>${variant.quantity}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary select-variant-btn">
                        Chọn
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
    
    $('#variantsTable').show();
}

/**
 * Bind events cho việc chọn biến thể
 */
function bindVariantSelectionEvents(variants) {
    // Xử lý khi chọn color hoặc size
    $('#colorSelect, #sizeSelect').on('change', function() {
        const selectedColor = $('#colorSelect').val();
        const selectedSize = $('#sizeSelect').val();
        
        console.log('Selected color:', selectedColor, 'Selected size:', selectedSize); // Debug
        
        if (selectedColor && selectedSize) {
            // Tìm biến thể phù hợp
            const variant = variants.find(v => 
                v.color_id == selectedColor && v.size_id == selectedSize
            );
            
            console.log('Found variant:', variant); // Debug
            
            if (variant) {
                displaySelectedVariant(variant);
            } else {
                hideSelectedVariant();
            }
        } else {
            hideSelectedVariant();
        }
    });
    
    // Xử lý khi click chọn biến thể từ bảng
    $(document).on('click', '.select-variant-btn', function() {
        const row = $(this).closest('tr');
        const colorId = row.data('color-id');
        const sizeId = row.data('size-id');
        
        $('#colorSelect').val(colorId);
        $('#sizeSelect').val(sizeId);
        
        const variant = variants.find(v => 
            v.color_id == colorId && v.size_id == sizeId
        );
        
        if (variant) {
            displaySelectedVariant(variant);
        }
    });
    
    // Xử lý tăng/giảm số lượng
    $('#decreaseQuantity').on('click', function() {
        const input = $('#quantityInput');
        const currentValue = parseInt(input.val());
        if (currentValue > 1) {
            input.val(currentValue - 1);
        }
    });
    
    $('#increaseQuantity').on('click', function() {
        const input = $('#quantityInput');
        const currentValue = parseInt(input.val());
        const maxQuantity = parseInt($('#variantQuantity').text());
        if (currentValue < maxQuantity) {
            input.val(currentValue + 1);
        }
    });
    
    // Xử lý input số lượng
    $('#quantityInput').on('input', function() {
        const value = parseInt($(this).val());
        const maxQuantity = parseInt($('#variantQuantity').text());
        
        if (value > maxQuantity) {
            $(this).val(maxQuantity);
        } else if (value < 1) {
            $(this).val(1);
        }
    });
}

/**
 * Hiển thị thông tin biến thể đã chọn
 */
function displaySelectedVariant(variant) {
    console.log('Displaying variant:', variant); // Debug
    
    $('#variantPrice').text('$' + parseFloat(variant.price).toFixed(2));
    $('#variantQuantity').text(variant.quantity);
    $('#selectedVariantInfo').show();
    $('#quantitySelection').show();
    
    // Enable nút thêm vào giỏ hàng
    $('#addToCartBtn').prop('disabled', false);
    
    // Set max quantity cho input
    $('#quantityInput').attr('max', variant.quantity);
    
    console.log('Button enabled:', $('#addToCartBtn').prop('disabled')); // Debug
}

/**
 * Ẩn thông tin biến thể đã chọn
 */
function hideSelectedVariant() {
    $('#selectedVariantInfo').hide();
    $('#quantitySelection').hide();
    $('#addToCartBtn').prop('disabled', true);
}

/**
 * Thêm sản phẩm vào giỏ hàng từ wishlist
 */
function addToCartFromWishlist() {
    console.log('Adding to cart...'); // Debug
    
    const productId = $('#modalProductName').data('product-id');
    const colorId = $('#colorSelect').val();
    const sizeId = $('#sizeSelect').val();
    const quantity = parseInt($('#quantityInput').val());
    
    console.log('Product ID:', productId, 'Color ID:', colorId, 'Size ID:', sizeId, 'Quantity:', quantity); // Debug
    
    if (!colorId || !sizeId || !quantity) {
        showErrorToast('Vui lòng chọn đầy đủ thông tin biến thể và số lượng');
        return;
    }
    
    // Disable button
    $('#addToCartBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Đang thêm...');
    
    $.ajax({
        url: '/client/add-to-cart',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json',
        },
        data: JSON.stringify({
            product_id: productId,
            color_id: colorId,
            size_id: sizeId,
            quantity: quantity
        }),
        dataType: 'json',
        success: function(response) {
            console.log('Success response:', response); // Debug
            
            if (response.status === 'success') {
                showSuccessToast('Đã thêm sản phẩm vào giỏ hàng thành công!');
                
                // Cập nhật số lượng giỏ hàng trong header nếu có
                if (response.cart_count !== undefined) {
                    updateCartCount(response.cart_count);
                }
                
                // Đóng modal
                $('#productVariantsModal').modal('hide');
            } else {
                showErrorToast(response.message || 'Không thể thêm vào giỏ hàng');
            }
        },
        error: function(xhr) {
            console.log('Error response:', xhr); // Debug
            
            let errorMessage = 'Không thể thêm vào giỏ hàng';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showErrorToast(errorMessage);
        },
        complete: function() {
            // Re-enable button
            $('#addToCartBtn').prop('disabled', false).html('<i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng');
        }
    });
}

/**
 * Cập nhật số lượng giỏ hàng trong header
 */
function updateCartCount(count) {
    const cartCountElement = $('.cart-dropdown .label');
    if (cartCountElement.length > 0) {
        cartCountElement.text(count);
    }
}

// Khởi tạo khi document ready
$(document).ready(function() {
    initializeWishlist();
});

