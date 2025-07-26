document.addEventListener('DOMContentLoaded', function () {
    const colorSquares = document.querySelectorAll('.color-square');
    const sizeOptions = document.querySelectorAll('.size-option');
    const priceDisplay = document.querySelector('.price-detail');
    const quantityDisplay = document.querySelector('.variant-quantity');

    let selectedColorId = null;
    let selectedSizeId = null;

    function updatePriceAndQuantity() {
        const matchedVariant = variants.find(v =>
            v.color_id == selectedColorId &&
            v.size_id == selectedSizeId
        );

        if (matchedVariant && matchedVariant.quantity > 0) {
            priceDisplay.textContent = Number(matchedVariant.price).toLocaleString('vi-VN') + '₫';
            quantityDisplay.textContent = 'Còn hàng';
            document.getElementById('productVariantId').value = matchedVariant.id;
        } else {
            priceDisplay.textContent = 'Không tìm thấy giá';
            quantityDisplay.textContent = 'Hết hàng';
            document.getElementById('productVariantId').value = 0;
        }

        document.getElementById('selectedColor').value = selectedColorId ?? '';
        document.getElementById('selectedSize').value = selectedSizeId ?? '';
    }

    function selectSize(sizeEl) {
        const isDisabled = sizeEl.classList.contains('is-disabled');

        sizeOptions.forEach(opt => {
            opt.classList.remove('selected');
            opt.parentElement.classList.remove('active');
        });

        if (!isDisabled) {
            sizeEl.classList.add('selected');
            sizeEl.parentElement.classList.add('active');
            selectedSizeId = parseInt(sizeEl.getAttribute('data-size-id'));
        } else {
            selectedSizeId = parseInt(sizeEl.getAttribute('data-size-id'));
        }

        updatePriceAndQuantity();
    }

    function selectColor(colorEl) {
        colorSquares.forEach(sq => sq.classList.remove('selected'));
        colorEl.classList.add('selected');
        selectedColorId = parseInt(colorEl.getAttribute('data-color-id'));

        sizeOptions.forEach(option => {
            const sizeId = parseInt(option.getAttribute('data-size-id'));
            const variant = variants.find(v =>
                v.color_id == selectedColorId &&
                v.size_id == sizeId
            );

            option.classList.remove('selected', 'is-disabled');
            option.parentElement.classList.remove('active');
            option.style.pointerEvents = 'auto';
            option.style.opacity = '1';

            if (!variant || variant.quantity === 0) {
                option.classList.add('is-disabled');
                option.style.pointerEvents = 'auto'; // Vẫn cho click để xử lý
                option.style.opacity = '0.5';
            }
        });

        const firstValidSize = [...sizeOptions].find(option =>
            !option.classList.contains('is-disabled')
        );

        if (firstValidSize) {
            selectSize(firstValidSize);
        } else {
            selectedSizeId = null;
            priceDisplay.textContent = 'Không tìm thấy giá';
            quantityDisplay.textContent = 'Hết hàng';
            document.getElementById('productVariantId').value = 0;
            document.getElementById('selectedSize').value = '';
        }
    }

    // Gắn sự kiện click
    colorSquares.forEach(square => {
        square.addEventListener('click', function () {
            selectColor(this);
        });
    });

    sizeOptions.forEach(option => {
        option.addEventListener('click', function () {
            selectSize(this);
        });
    });

    // Khởi tạo với màu đầu tiên
    if (colorSquares.length > 0) {
        selectColor(colorSquares[0]);
    }
});
