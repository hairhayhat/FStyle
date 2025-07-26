$(document).ready(function () {
    const searchUrl = $('#global-search').data('search-url');

    $('#global-search').on('keyup', function () {
        let query = $(this).val();

        if (query.length > 2) {
            $.ajax({
                url: searchUrl,
                type: 'GET',
                data: { query: query },
                success: function (response) {
                    let results = '';

                    const products = response.products || [];
                    const categories = response.categories || [];

                    if (categories.length > 0) {
                        categories.forEach(function (category) {
                            results += `
                    <li>
                        <div class="product-cart">
                            <div class="product-info">
                                <a href="/category/${category.slug}">
                                    <h6>${category.name}</h6>
                                </a>
                            </div>
                            <img src="${category.image}" alt="${category.name}">
                        </div>
                    </li>
                `;
                        });
                    }

                    if (products.length > 0) {
                        products.forEach(function (product) {
                            results += `
                    <li>
                        <div class="product-cart">
                            <div class="product-info">
                                <a href="/product/${product.slug}">
                                    <h6>${product.name}</h6>
                                </a>
                            </div>
                            <img src="${product.image}" alt="${product.name}">
                        </div>
                    </li>
                `;
                        });
                    }

                    if (!results) {
                        results = `<li><p class="px-3 py-2 mb-0 text-center">Không có kết quả phù hợp</p></li>`;
                    }

                    $('#search-results').html(results);
                }
            });

        } else {
            $('#search-results').empty();
        }
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.search-full').length) {
            $('#search-results').empty();
        }
    });
});
