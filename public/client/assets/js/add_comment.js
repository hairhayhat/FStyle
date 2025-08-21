$('.btn-show-order').click(function () {
    var orderCode = $(this).data('order-code');

    $.ajax({
        url: '/client/checkout/apiDetail/' + orderCode,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var modalBody = $('#addComment .modal-body');
            modalBody.empty();

            $.each(data.items, function (index, item) {
                var priceFormatted = Number(item.price).toLocaleString('vi-VN') + 'đ/cái';
                var html = `
<div class="mb-3 border-bottom pb-2 comment-item" data-order-detail-id="${item.order_detail_id}">
    <div class="d-flex align-items-start">
        <!-- Thông tin sản phẩm bên trái -->
        <div class="flex-grow-1 pe-3">
            <p><strong>${item.product_name}</strong> ${item.variant_name}</p>
            <p class="text-muted small">
                Size: ${item.size ?? 'N/A'} | Màu: ${item.color ?? 'N/A'}
            </p>
            <p>Số lượng: ${item.quantity}</p>
            <p>Giá: ${priceFormatted}</p>

            <!-- Rating 5 sao -->
            <div class="mb-2">
                <label class="form-label fw-bold">Đánh giá:</label>
                <div class="rating-stars" data-id="${item.order_detail_id}">
                    <span class="star" data-value="1">&#9733;</span>
                    <span class="star" data-value="2">&#9733;</span>
                    <span class="star" data-value="3">&#9733;</span>
                    <span class="star" data-value="4">&#9733;</span>
                    <span class="star" data-value="5">&#9733;</span>
                    <input type="hidden" name="comments[${item.order_detail_id}][rating]" value="0" class="rating-value" required>
                    <input type="hidden" name="comments[${item.order_detail_id}][product_id]" value="${item.product_id}">
                </div>
            </div>
        </div>

        <!-- Ảnh sản phẩm bên phải -->
        <div class="flex-shrink-0" style="width: 160px; height: 160px;">
            <img src="${item.image_url}" alt="${item.product_name}" class="img-fluid rounded border">
        </div>
    </div>

    <!-- Textarea, checkbox, upload media bên dưới -->
    <div class="mt-2">
        <div class="mb-2">
            <textarea name="comments[${item.order_detail_id}][content]" class="form-control" rows="2" placeholder="Nhận xét"></textarea>
        </div>

        <div class="mb-2 form-check">
            <input type="checkbox" class="form-check-input" name="comments[${item.order_detail_id}][is_accurate]" value="1" id="accurate_${item.order_detail_id}">
            <label class="form-check-label" for="accurate_${item.order_detail_id}">Sản phẩm đúng mô tả</label>
        </div>

        <div class="mb-2">
            <input type="file" class="form-control comment-media" name="comments[${item.order_detail_id}][media][]" multiple data-order-detail-id="${item.order_detail_id}">
            <small class="text-muted">Có thể chọn nhiều ảnh/video</small>
        </div>
    </div>
</div>
`;
                modalBody.append(html);
            });

            $('#addComment').modal('show');
        },
        error: function (err) {
            console.error(err);
            alert('Không tải được chi tiết đơn hàng.');
        }
    });
});

// Star rating functionality
$(document).on('mouseenter', '.rating-stars .star', function () {
    var $stars = $(this).parent().find('.star');
    var value = $(this).data('value');
    $stars.each(function () {
        $(this).toggleClass('hover', $(this).data('value') <= value);
    });
});

$(document).on('mouseleave', '.rating-stars .star', function () {
    $(this).parent().find('.star').removeClass('hover');
});

$(document).on('click', '.rating-stars .star', function () {
    var $container = $(this).closest('.rating-stars');
    var value = $(this).data('value');

    $container.find('.star').each(function () {
        $(this).toggleClass('selected', $(this).data('value') <= value);
    });

    $container.find('input.rating-value').val(value);
});

$('#addComment form').on('submit', function (e) {
    var hasError = false;

    $('.rating-value').each(function () {
        if ($(this).val() == '0') {
            hasError = true;
            $(this).closest('.comment-item').addClass('border-danger');
            alert('Vui lòng đánh giá sao cho tất cả sản phẩm');
        }
    });

    if (hasError) {
        e.preventDefault();
        return false;
    }

    return true;
});
