$(document).ready(function () {
    priceFilter();

    $('.check-it, .color-radio, .size-radio, .js-range-slider').on('change', fetchProducts);

    function fetchProducts() {
        const selectedCategories = $('.check-it:checked').map(function () {
            return $(this).siblings('label').text().trim();
        }).get();

        const selectedColor = $('.color-radio:checked').val();
        const selectedSize = $('.size-radio:checked').val();

        const priceRange = $('.js-range-slider').data('ionRangeSlider');
        const priceFrom = priceRange.old_from;
        const priceTo = priceRange.old_to;

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

    function priceFilter() {
        const $range = $(".js-range-slider");
        const $inputFrom = $(".js-input-from");
        const $inputTo = $(".js-input-to");

        $range.ionRangeSlider({
            type: "double",
            min: 0,
            max: 1000000,
            from: 0,
            to: 300000,
            prefix: "vnđ",
            step: 100,
            prettify_separator: ".",
            force_edges: true,
            onStart: updateInputs,
            onChange: updateInputs,
        });

        const instance = $range.data("ionRangeSlider");

        function updateInputs(data) {
            $inputFrom.val(data.from);
            $inputTo.val(data.to);
        }

        $inputFrom.on("input", function () {
            let val = +$(this).val();
            if (val < 0) val = 0;
            if (val > instance.result.to) val = instance.result.to;
            instance.update({ from: val });
        });

        $inputTo.on("input", function () {
            let val = +$(this).val();
            if (val < instance.result.from) val = instance.result.from;
            if (val > 1000000) val = 1000000;
            instance.update({ to: val });
        });
    }
});
