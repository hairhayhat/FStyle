$(document).ready(function () {
    $('.check-it, .color-radio, .size-radio').on('change', fetchProducts);
    $('.js-range-slider').on('change', fetchProducts);

    function fetchProducts() {
        const selectedCategories = $('.check-it:checked').map(function () {
            return $(this).siblings('label').text().trim();
        }).get();

        const selectedColor = $('.color-radio:checked').val();
        const selectedSize = $('.size-radio:checked').val();

        const priceRange = $('.js-range-slider').data('ionRangeSlider');
        const priceFrom = priceRange.result.from;
        const priceTo = priceRange.result.to;

        $.ajax({
            url: '/filter-products',
            type: 'GET',
            data: {
                categories: selectedCategories,
                color: selectedColor,
                size: selectedSize,
                price_from: priceFrom,
                price_to: priceTo
            },
            success: function (res) {
                $('.product-list-section').html(res);

                if ($('.product-list-section').find('.product-box').length === 0) {
                    $('.product-list-section').html(`
                        <div class="text-center py-5">
                            <h4 class="text-muted">Không tìm thấy sản phẩm nào phù hợp.</h4>
                        </div>
                    `);
                } else {
                    feather.replace();
                    $('.slick-slider').not('.slick-initialized').slick();
                }
            }


        });
    }
})
