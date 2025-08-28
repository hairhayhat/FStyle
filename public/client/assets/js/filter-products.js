$(document).ready(function () {
    priceFilter();
    let debounceTimer;

    $('.check-it, .color-radio, .size-radio').on('change', function () {
        updateFilterTags();
        fetchProducts(1);
    });

    $('.js-range-slider').on('change', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            updateFilterTags();
            fetchProducts(1);
        }, 300);
    });

    $('.form-select[aria-label="Sort by"], .form-select[aria-label="Products per page"]').on('change', function () {
        fetchProducts(1);
    });

    $(document).on('click', '.label-tag .btn-close', function () {
        const text = $(this).siblings('span').text().trim();

        $('.check-it').each(function () {
            if ($(this).siblings('label').text().trim() === text) $(this).prop('checked', false);
        });
        $('.color-radio, .size-radio').each(function () {
            if ($(this).data('name') === text) $(this).prop('checked', false);
        });

        if (text.includes('vnđ')) {
            const slider = $(".js-range-slider").data("ionRangeSlider");
            slider.update({ from: 0, to: 1000000 });
        }

        updateFilterTags();
        fetchProducts(1);
    });

    $(document).on('click', '.label-tag a', function () {
        $('.check-it, .color-radio, .size-radio').prop('checked', false);
        const slider = $(".js-range-slider").data("ionRangeSlider");
        slider.update({ from: 0, to: 1000000 });
        updateFilterTags();
        const currentSlug = window.location.pathname.split('/').pop();
        window.location.href = '/category/' + currentSlug;
    });

    $(document).on('click', '.product-list-section .pagination a', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let page = 1;
        if (url.indexOf('page=') !== -1) {
            page = url.split('page=')[1].split('&')[0];
        }
        page = parseInt(page) || 1;
        fetchProducts(page);
    });

    function updateFilterTags() {
        const $tagContainer = $('.short-name');
        $tagContainer.empty();

        $('.check-it:checked').each(function () {
            const text = $(this).siblings('label').text().trim();
            $tagContainer.append(`<li><div class="label-tag"><span>${text}</span><button type="button" class="btn-close" aria-label="Close"></button></div></li>`);
        });

        const colorName = $('.color-radio:checked').data('name');
        if (colorName) $tagContainer.append(`<li><div class="label-tag"><span>${colorName}</span><button type="button" class="btn-close" aria-label="Close"></button></div></li>`);

        const sizeName = $('.size-radio:checked').data('name');
        if (sizeName) $tagContainer.append(`<li><div class="label-tag"><span>${sizeName}</span><button type="button" class="btn-close" aria-label="Close"></button></div></li>`);

        const priceRange = $('.js-range-slider').data('ionRangeSlider');
        if (priceRange) $tagContainer.append(`<li><div class="label-tag"><span>${priceRange.result.from} - ${priceRange.result.to} vnđ</span><button type="button" class="btn-close" aria-label="Close"></button></div></li>`);

        $tagContainer.append(`<li><div class="label-tag"><a href="javascript:void(0)"><span>Clear All</span></a></div></li>`);
    }

    function fetchProducts(page = 1) {
        const selectedCategories = $('.check-it:checked').map(function () { return $(this).siblings('label').text().trim(); }).get();
        const selectedColor = $('.color-radio:checked').val();
        const selectedSize = $('.size-radio:checked').val();
        const priceRange = $('.js-range-slider').data('ionRangeSlider');
        const priceFrom = priceRange.old_from;
        const priceTo = priceRange.old_to;
        const sortBy = $('.form-select[aria-label="Sort by"]').val();
        const perPage = $('.form-select[aria-label="Products per page"]').val();

        if ($('.loading-overlay').length === 0) {
            $('.product-list-section').append(`<div class="loading-overlay" style="position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(255,255,255,0.7); z-index:9999; text-align:center;"><div class="spinner-border text-primary" style="margin-top:20%;" role="status"></div></div>`);
        }
        $('.loading-overlay').show();

        $.ajax({
            url: '/filter-products',
            type: 'GET',
            data: { categories: selectedCategories, color: selectedColor, size: selectedSize, price_from: priceFrom, price_to: priceTo, sort: sortBy, per_page: perPage, page: page },
            success: function (res) {
                $('.product-list-section').html(res);
                feather.replace();
                $('.slick-slider').not('.slick-initialized').slick();
                let url = window.location.pathname + '?';
                if (selectedCategories.length) url += 'categories[]=' + selectedCategories.join('&categories[]=') + '&';
                if (selectedColor) url += 'color=' + selectedColor + '&';
                if (selectedSize) url += 'size=' + selectedSize + '&';
                url += 'price_from=' + priceFrom + '&price_to=' + priceTo + '&sort=' + sortBy + '&per_page=' + perPage + '&page=' + page;
                window.history.pushState(null, '', url);
            },
            complete: function () { $('.loading-overlay').hide(); }
        });
    }

    function priceFilter() {
        const $range = $(".js-range-slider");
        const $inputFrom = $(".js-input-from");
        const $inputTo = $(".js-input-to");

        $range.ionRangeSlider({ type: "double", min: 0, max: 1000000, from: 0, to: 1000000, prefix: "vnđ", step: 100, prettify_separator: ".", force_edges: true, onStart: updateInputs, onChange: updateInputs });
        const instance = $range.data("ionRangeSlider");

        function updateInputs(data) { $inputFrom.val(data.from); $inputTo.val(data.to); }
        $inputFrom.on("input", function () { let val = +$(this).val(); if (val < 0) val = 0; if (val > instance.result.to) val = instance.result.to; instance.update({ from: val }); });
        $inputTo.on("input", function () { let val = +$(this).val(); if (val < instance.result.from) val = instance.result.from; if (val > 1000000) val = 1000000; instance.update({ to: val }); });
    }

    updateFilterTags();
});
