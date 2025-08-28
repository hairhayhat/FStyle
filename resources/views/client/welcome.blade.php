@extends('client.layouts.app')

@section('content')
    <section class="home-section home-style-2 pt-0">
        <div class="container-fluid p-0">
            <div class="slick-2 dot-dark">
                <div>
                    <div class="home-slider">
                        <div class="home-wrap row m-0">
                            <div class="col-xxl-3 col-lg-4 p-0 d-lg-block d-none">
                                <div class="home-left-wrapper">
                                    <div>
                                        <h2>Hot Trend 2025 – Số Lượng Có Hạn!</h2>
                                        <p>Khám phá xu hướng thời trang 2025 với thiết kế tinh tế, chất liệu cao cấp và
                                            phong cách độc đáo.
                                            <br><span class="theme-color fw-bold">Ưu đãi đặc biệt chỉ trong tuần này!</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-9 col-lg-8 p-0 left-content">
                                <img src="{{ asset('client/assets/images/fashion/slider/1.jpg') }}"
                                    class="bg-img blur-up lazyload" alt="slider">
                                <div class="home-content row">
                                    <div class="col-xxl-4 col-lg-4 col-md-4 col-sm-5 col-6">
                                        <h3><span class="theme-color">Thời Trang Nam</span></h3>
                                        <h1 data-animation-in="fadeInUp">Phong Cách <br> Hiện Đại</h1>
                                        <h6 class="mb-4" data-animation-in="fadeInUp" data-delay-in="0.4">
                                            <span class="theme-color">Sale đến 50%</span>
                                        </h6>
                                        <div class="discover-block" data-animation-in="fadeInUp" data-delay-in="0.7">
                                            <div class="d-flex">
                                                <a href="javascript:void(0)" class="play-icon theme-bg-color">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                                <div class="discover-content">
                                                    <h4 class="theme-color mb-1">Khám Phá Ngay</h4>
                                                    <h6>Bộ sưu tập xuân hè 2025</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4" data-animation-in="fadeInUp" data-delay-in="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="home-slider">
                        <div class="home-wrap row m-0">
                            <!-- Nội dung bên trái -->
                            <div class="col-xxl-3 col-lg-4 p-0 d-lg-block d-none">
                                <div class="home-left-wrapper">
                                    <div>
                                        <h2>Thời Trang Cá Tính – Sẵn Sàng Tỏa Sáng</h2>
                                        <p>
                                            Thiết kế trẻ trung, phối màu cá tính – chất vải cotton thoáng mát, bền đẹp.
                                            Lựa chọn hoàn hảo cho những ngày năng động và tự tin.
                                            <br><span class="theme-color fw-bold">Số lượng giới hạn!</span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Nội dung bên phải -->
                            <div class="col-xxl-9 col-lg-8 p-0 left-content">
                                <img src="{{ asset('client/assets/images/fashion/slider/2.jpg') }}"
                                    class="bg-img blur-up lazyload" alt="slider">

                                <div class="home-content row">
                                    <div class="col-xxl-4 col-lg-5 col-md-6 col-sm-7 col-9">
                                        <h3>Ưu đãi <span class="theme-color">70% OFF</span></h3>
                                        <h1 data-animation-in="fadeInUp">Phong Cách <br> Xu Hướng</h1>
                                        <h6 class="mb-4" data-animation-in="fadeInUp" data-delay-in="0.4">
                                            Mua 1 tặng 1 <span class="theme-color">Miễn Phí</span>
                                        </h6>

                                        <div class="discover-block" data-animation-in="fadeInUp" data-delay-in="0.7">
                                            <div class="d-flex">
                                                <a href="javascript:void(0)" class="play-icon theme-bg-color">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                                <div class="discover-content">
                                                    <h4 class="theme-color mb-1">Khám Phá Ngay</h4>
                                                    <h6>Bộ sưu tập nổi bật 2025</h6>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4" data-animation-in="fadeInUp" data-delay-in="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ratio_asos">
        <div class="container">
            <div class="row m-0">
                <div class="col-sm-12 p-0">
                    <div class="title title-2 text-center">
                        <h2>Sản phẩm mới</h2>
                        <h5 class="text-color">Bộ sưu tập của chúng tôi</h5>
                    </div>
                    <div class="product-wrapper product-style-2 slide-4 p-0 light-arrow bottom-space">
                        @foreach ($newProducts as $item)
                            <div>
                                <div class="product-box">
                                    <div class="img-wrapper">
                                        <div class="front">
                                            <a href="{{ route('product.detail', ['slug' => $item->slug]) }}">
                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                    class="bg-img blur-up lazyload" alt="">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="{{ route('product.detail', ['slug' => $item->slug]) }}">
                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                    class="bg-img blur-up lazyload" alt="">
                                            </a>
                                        </div>
                                        <div class="cart-wrap">
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0)" class="addtocart-btn"
                                                        data-bs-toggle="modal" data-bs-target="#addtocart">
                                                        <i data-feather="shopping-bag"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="quick-view-btn"
                                                        data-url={{ route('product.detail.api', ['slug' => $item->slug]) }}>
                                                        <i data-feather="eye"></i>
                                                    </button>
                                                </li>
                                                <li>
                                                    <a href="compare.html">
                                                        <i data-feather="refresh-cw"></i>
                                                    </a>
                                                </li>
                                                @auth
                                                    <li>
                                                        <button type="button" class="favorite-toggle"
                                                            data-slug="{{ $item->slug }}"
                                                            data-product-id="{{ $item->id }}"
                                                            data-is-favorited="{{ in_array($item->id, $favoriteProductIds ?? []) ? 'true' : 'false' }}">
                                                            <i
                                                                class="heart-icon {{ in_array($item->id, $favoriteProductIds ?? []) ? 'fas fa-heart text-danger' : 'far fa-heart' }}"></i>
                                                        </button>
                                                    </li>
                                                @endauth

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-details">
                                        <div class="rating-details">
                                            <span class="font-light grid-content">{{ $item->Category->name }}</span>
                                            @php
                                                $avgRating = round($item->activeComments->avg('rating'), 1);
                                            @endphp
                                            <ul class="rating mt-0">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($avgRating))
                                                        <li><i class="fas fa-star theme-color"></i></li>
                                                    @elseif ($i == ceil($avgRating) && $avgRating - floor($avgRating) >= 0.5)
                                                        <li><i class="fas fa-star-half-alt theme-color"></i></li>
                                                    @else
                                                        <li><i class="fas fa-star"></i></li>
                                                    @endif
                                                @endfor
                                            </ul>
                                        </div>
                                        <div class="main-price d-flex justify-content-between align-items-center">
                                            <a href="{{ route('product.detail', ['slug' => $item->slug]) }}"
                                                class="font-default text-decoration-none">
                                                <h6 class="fw-bold mb-0">{{ $item->name }}</h6>
                                            </a>
                                            <div>
                                                <span class="theme-color">
                                                    @if ($item->variants->min('sale_price') == $item->variants->max('sale_price'))
                                                        {{ number_format($item->variants->min('sale_price')) }} Vnđ
                                                    @else
                                                        {{ number_format($item->variants->min('sale_price')) }} -
                                                        {{ number_format($item->variants->max('sale_price')) }} Vnđ
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product section end -->

    <!-- category section start -->
    <section class="category-section ratio_40">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="title title-2 text-center">
                        <h2>Danh Mục</h2>
                        <h5 class="text-color">Bộ sưu tập của chúng tôi</h5>
                    </div>
                </div>
            </div>
            <div class="row gy-3">
                <div class="col-xxl-2 col-lg-3">
                    <div class="category-wrap category-padding category-block theme-bg-color">
                        <div>
                            <h2 class="light-text"></h2>
                            <h2 class="top-spacing">Danh Mục</h2>
                            <span>Hàng đầu</span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-10 col-lg-9">
                    <div class="category-wrapper category-slider1 white-arrow category-arrow">
                        @foreach ($categories as $item)
                            <div>
                                <a href="{{ route('search.category', ['slug' => $item->slug]) }}"
                                    class="category-wrap category-padding">
                                    <img src="{{ asset('storage/' . $item->image) }}" class="bg-img blur-up lazyload"
                                        alt="category image">
                                    <div class="category-content category-text-1">
                                        <h3 class="theme-color">{{ $item->name }}</h3>
                                        <span class="text-dark">Thời trang</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- category section end -->

    <!-- banner section 2 start -->

    <section class="timer-banner-style-2">
        <div class="container">
            <div class="row gy-3">
                <div class="col-lg-12">
                    <div class="title title-2 text-center">
                        <h2>Thời gian giảm giá</h2>
                        <h5 class="text-color">Dành cho bạn </h5>
                    </div>
                    <div class="discount-image-details discount-spacing">
                        <img src="client/assets/images/fashion/banner/8.jpg" class="bg-img blur-up lazyload"
                            alt="">
                        <div class="discunt-details">
                            <div>
                                <div class="heart-button heart-button-2">
                                    <i class="fas fa-heart theme-color"></i>
                                </div>
                                <h5 class="mt-3">Special Discount <span class="theme-color">70% OFF</span></h5>
                                <h2 class="my-3 deal-text">Deal Of The Day <br>from <span class="theme-color">$75</span>
                                </h2>
                                <div class="timer-style-2 mt-xl-1 my-2 justify-content-center d-flex">
                                    <ul>
                                        <li>
                                            <div class="counter">
                                                <div>
                                                    <h2 id="days1" class="theme-color"></h2>Days
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="counter">
                                                <div>
                                                    <h2 id="hours1" class="theme-color"></h2>Hour
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="counter">
                                                <div>
                                                    <h2 id="minutes1" class="theme-color"></h2>Min
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="counter">
                                                <div>
                                                    <h2 id="seconds1" class="theme-color"></h2>Sec
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <button onclick="location.href = 'shop-left-sidebar.html';" type="button"
                                    class="btn default-light-theme default-theme mt-2">Shop
                                    Now</button>
                                <div class="timer-bg timer-bg-center d-lg-block d-none">
                                    <h3 class="mt-0">Latest Jacket</h3>
                                    <span>BUY ONE GET ONE FREE</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner section 2 end -->

    <!-- product section 2 start -->
    <section class="ratio_asos">
        <div class="container">
            <div class="row m-0">
                <div class="col-sm-12 p-0">
                    <div class="title title-2 text-center">
                        <h2>Danh sách sản phẩm</h2>
                        <h5 class="text-color">Bộ sưu tập của chúng tôi </h5>
                    </div>
                    <div class="product-wrapper product-style-2 row g-sm-4 g-3">
                        @foreach ($products as $item)
                            <div class="col-xl-3 col-lg-4 col-6">
                                <div class="product-box">
                                    <div class="img-wrapper">
                                        <div class="front">
                                            <a href="{{ route('product.detail', ['slug' => $item->slug]) }}">
                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                    class="bg-img blur-up lazyload" alt="">
                                            </a>
                                        </div>
                                        <div class="back">
                                            <a href="{{ route('product.detail', ['slug' => $item->slug]) }}">
                                                <img src="{{ asset('storage/' . $item->image) }}"
                                                    class="bg-img blur-up lazyload" alt="">
                                            </a>
                                        </div>
                                        {{-- <div class="label-block">
                                        <span class="label label-black">New</span>
                                        <span class="label label-theme">50% Off</span>
                                    </div> --}}
                                        <div class="cart-wrap">
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0)" class="addtocart-btn"
                                                        data-bs-toggle="modal" data-bs-target="#addtocart">
                                                        <i data-feather="shopping-bag"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="quick-view-btn"
                                                        data-url={{ route('product.detail.api', ['slug' => $item->slug]) }}>
                                                        <i data-feather="eye"></i>
                                                    </button>
                                                </li>
                                                <li>
                                                    <a href="compare.html">
                                                        <i data-feather="refresh-cw"></i>
                                                    </a>
                                                </li>
                                                @auth
                                                    <li>
                                                        <button type="button" class="favorite-toggle"
                                                            data-slug="{{ $item->slug }}"
                                                            data-product-id="{{ $item->id }}"
                                                            data-is-favorited="{{ in_array($item->id, $favoriteProductIds ?? []) ? 'true' : 'false' }}">
                                                            <i
                                                                class="heart-icon {{ in_array($item->id, $favoriteProductIds ?? []) ? 'fas fa-heart text-danger' : 'far fa-heart' }}"></i>
                                                        </button>
                                                    </li>
                                                @endauth

                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-details">
                                        <div class="rating-details">
                                            <span class="font-light grid-content">{{ $item->Category->name }}</span>
                                            @php
                                                $avgRating = round($item->activeComments->avg('rating'), 1);
                                            @endphp
                                            <ul class="rating mt-0">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($avgRating))
                                                        <li><i class="fas fa-star theme-color"></i></li>
                                                    @elseif ($i == ceil($avgRating) && $avgRating - floor($avgRating) >= 0.5)
                                                        <li><i class="fas fa-star-half-alt theme-color"></i></li>
                                                    @else
                                                        <li><i class="fas fa-star"></i></li>
                                                    @endif
                                                @endfor
                                            </ul>
                                        </div>
                                        <div class="main-price d-flex justify-content-between align-items-center">
                                            <a href="{{ route('product.detail', ['slug' => $item->slug]) }}"
                                                class="font-default text-decoration-none">
                                                <h6 class="fw-bold mb-0">{{ $item->name }}</h6>
                                            </a>
                                            <div>
                                                <span class="theme-color">
                                                    @if ($item->variants->min('sale_price') == $item->variants->max('sale_price'))
                                                        {{ number_format($item->variants->min('sale_price')) }} Vnđ
                                                    @else
                                                        {{ number_format($item->variants->min('sale_price')) }} -
                                                        {{ number_format($item->variants->max('sale_price')) }} Vnđ
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- <div class="pagination-box d-flex justify-content-center">
                                {{ $products->links('pagination::bootstrap-4') }}
                            </div> --}}
                        @if ($products->hasPages())
                            <nav class="page-section">
                                <ul class="pagination">
                                    {{-- Nút Previous --}}
                                    @if ($products->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $products->previousPageUrl() }}">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    {{-- Số trang --}}
                                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                        @if ($page == $products->currentPage())
                                            <li class="page-item active"><span
                                                    class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach

                                    {{-- Nút Next --}}
                                    @if ($products->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $products->nextPageUrl() }}">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Nếu có lưu vị trí scroll, cuộn lại
                if (sessionStorage.getItem("scrollPos")) {
                    window.scrollTo(0, sessionStorage.getItem("scrollPos"));
                    sessionStorage.removeItem("scrollPos");
                }

                // Khi click phân trang, lưu vị trí scroll
                document.querySelectorAll('.pagination a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        sessionStorage.setItem("scrollPos", window.scrollY);
                    });
                });
            });
        </script>
    </section>







    <!-- product section 2 end -->

    <!-- Banner section 3 start -->

    <!-- Banner section 3 end -->

    <!-- instagram shop section start -->


    <div class="modal fade quick-view-modal" id="quick-view">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-4">
                    <div class="row gy-4">
                        <!-- Phần hình ảnh sản phẩm -->
                        <div class="col-lg-6">
                            <div class="quick-view-image">
                                <div class="quick-view-slider ratio_2">
                                    <!-- Ảnh chính của sản phẩm -->
                                    <div>
                                        <img src="" class="img-fluid main-product-image" alt="product">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Phần thông tin sản phẩm -->
                        <div class="col-lg-6">
                            <div class="product-right">
                                <h2 class="mb-2 product-name">Loading...</h2>

                                <div class="d-flex align-items-center">
                                    <ul class="rating mt-1 me-2">
                                        <!-- Rating sẽ được cập nhật bằng JS -->
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                        <li><i class="fas fa-star"></i></li>
                                    </ul>
                                    <span class="font-light stock-status">(In stock)</span>
                                </div>

                                <div class="price mt-3">
                                    <h3 class="product-price">$0.00</h3>
                                </div>

                                <!-- Màu sắc - sẽ được cập nhật bằng JS -->
                                <div class="color-types mt-3">
                                    <h4>colors</h4>
                                    <ul class="color-variant mb-0 color-options">
                                        <!-- JS sẽ thêm options màu ở đây -->
                                    </ul>
                                </div>

                                <!-- Kích thước - sẽ được cập nhật bằng JS -->
                                <div class="size-detail mt-3">
                                    <h4>size</h4>
                                    <ul class="size-options">
                                        <!-- JS sẽ thêm options size ở đây -->
                                    </ul>
                                </div>

                                <!-- Chi tiết sản phẩm -->
                                <div class="product-details mt-3">
                                    <h4>product details</h4>
                                    <ul class="product-details-list">
                                        <!-- JS sẽ thêm chi tiết ở đây -->
                                    </ul>
                                </div>

                                <!-- Nút hành động -->
                                <div class="product-btns mt-4">
                                    <a href="#" class="btn btn-solid-default btn-sm add-to-cart">Add to cart</a>
                                    <a href="#" class="btn btn-solid-default btn-sm view-details">View details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
                    $('.quick-view-btn').on('click', function() {
                        const url = $(this).data('url');
                        const modal = $('#quick-view');

                        // Reset trạng thái ban đầu
                        modal.find('.product-name').text('Loading...');
                        modal.find('.product-price').text('₫0');
                        modal.find('.stock-status').text('');
                        modal.find('.main-product-image').attr('src', '');
                        modal.find('.product-thumbnail').attr('src', '');
                        modal.find('.color-options').empty();
                        modal.find('.size-options').empty();
                        modal.find('.product-details-list').empty();

                        $.get(url, function(res) {
                            if (res.success && res.data) {
                                const data = res.data;

                                // Cập nhật tên và ảnh chính
                                modal.find('.product-name').text(data.name);
                                modal.find('.main-product-image').attr('src', data.main_image);

                                // Tính giá min - max (sử dụng sale_price thay vì price)
                                const prices = data.variants.map(v => Number(String(v.sale_price).replace(
                                    /,/g, '')));
                                const min = Math.min(...prices);
                                const max = Math.max(...prices);
                                const priceText = min === max ?
                                    `${min.toLocaleString()} ₫` :
                                    `${min.toLocaleString()} - ${max.toLocaleString()} ₫`;
                                modal.find('.product-price').text(priceText);

                                // Tình trạng kho
                                const quantity = data.variants.reduce((sum, v) => sum + v.quantity, 0);
                                modal.find('.stock-status').text(quantity > 0 ? '(Còn hàng)' :
                                    '(Hết hàng)');

                                // Màu sắc
                                const uniqueColors = [...new Set(data.variants.map(v => v.color || v
                                    .color_name || v.colorName || v.color?.name))];
                                const colorOptions = modal.find('.color-options');
                                uniqueColors.forEach(color => {
                                    if (color) {
                                        const safeColor = color.toLowerCase().replace(/\s/g, '-');
                                        colorOptions.append(
                                            `<li class="bg-${safeColor}" data-color="${color}" title="${color}"></li>`
                                        );
                                    }
                                });

                                // Kích thước
                                const uniqueSizes = [...new Set(data.variants.map(v => v.size || v
                                    .size_name || v.size?.name))];
                                const sizeOptions = modal.find('.size-options');
                                uniqueSizes.forEach(size => {
                                    if (size) {
                                        sizeOptions.append(`<li data-size="${size}">${size}</li>`);
                                    }
                                });

                                // Chi tiết sản phẩm
                                const detailsList = modal.find('.product-details-list');
                                detailsList.append(
                                    `<li><span class="font-light">Danh mục:</span> ${data.category}</li>`
                                );
                                detailsList.append(
                                    `<li><span class="font-light">Mô tả:</span> ${data.description}</li>`
                                );

                                // Link hành động
                                modal.find('.add-to-cart').attr('href', `/cart?product=${data.id}`);
                                modal.find('.view-details').attr('href', `/product/${data.id}`);

                                // Hiển thị modal
                                modal.modal('show');
                            } else {
                                alert('Không thể tải dữ liệu sản phẩm.');
                            }
                        }).fail(function() {
                            alert('Lỗi khi gọi API.');
                        });
                    });

                    $(document).on('click', '.favorite-toggle', function(e) {
                        e.preventDefault();
                        console.log("click");
                        handleFavoriteAction($(this));
                    })
    </script>
@endsection
