@extends('client.layouts.app')

@section('content')
    <section>
        <div class="container">
            <div class="row gx-4 gy-5">
                <div class="col-lg-9 col-12">
                    <!-- filter button -->
                    <div class="filter-button mb-3">
                        <button class="danger-button danger-center btn btn-sm filter-btn"><i data-feather="align-left"></i>
                            Filter</button>
                    </div>
                    <!-- filter button -->
                    <div class="details-items">
                        <div class="row g-4">
                            <div class="col-lg-5 col-md-6">
                                <div class="degree-section">
                                    <div class="details-image ratio_asos">
                                        <div>
                                            <div class="product-image-tag">
                                                <img src="{{ asset('storage/' . $product->image) }}" id="zoom_01"
                                                    data-zoom-image="{{ asset('storage/' . $product->image) }}"
                                                    class="img-fluid w-100 image_zoom_cls-0 blur-up lazyload"
                                                    alt="{{ $product->name }}">
                                            </div>
                                        </div>

                                        @if ($product->galleries->count() > 0)
                                            @foreach ($product->galleries as $key => $galleryImage)
                                                <div>
                                                    <div class="product-image-tag">
                                                        <img src="{{ asset('storage/' . $galleryImage->image) }}"
                                                            id="zoom_{{ $key + 2 }}"
                                                            data-zoom-image="{{ asset('storage/' . $galleryImage->image) }}"
                                                            class="img-fluid w-100 image_zoom_cls-{{ $key + 1 }} blur-up lazyload"
                                                            alt="{{ $product->name }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <div class="details-image-option black-slide mt-4 rounded">
                                        <div>
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="img-fluid blur-up lazyload" alt="{{ $product->name }} thumbnail">
                                        </div>

                                        @if ($product->galleries->count() > 0)
                                            @foreach ($product->galleries as $galleryImage)
                                                <div>
                                                    <img src="{{ asset('storage/' . $galleryImage->image) }}"
                                                        class="img-fluid blur-up lazyload"
                                                        alt="{{ $product->name }} thumbnail">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-7 col-md-6">
                                <div class="cloth-details-size">

                                    <div class="details-image-concept">
                                        <h2>{{ $product->name }}</h2>
                                    </div>

                                    <div class="label-section">
                                        <span class="label-text">{{ $product->category->name }}</span>
                                        <span class="label-text variant-quantity"></span>
                                    </div>

                                    <h3 class="price-detail"></h3>

                                    <form id="addToCartForm">
                                        @csrf

                                        <input type="hidden" name="product_variant_id" id="productVariantId">

                                        <div class="color-image">
                                            <div class="image-select">
                                                @php $colors = $product->variants->pluck('color')->unique('id'); @endphp
                                                <ul class="list-inline">
                                                    @foreach ($colors as $color)
                                                        <li class="list-inline-item">
                                                            <div class="color-square" data-color-id="{{ $color->id }}"
                                                                data-color-code="{{ $color->code }}"
                                                                style="background-color: {{ $color->code }};">
                                                                <i class="check-icon" style="display: none;">✔</i>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <div id="selectSize" class="addeffect-section product-description border-product">
                                            <h6 class="product-title size-text">Chọn kích cỡ</h6>
                                            <div class="size-box">
                                                <ul id="sizeList">
                                                    @foreach ($sizes as $item)
                                                        <li>
                                                            <a href="javascript:void(0)" data-size-id="{{ $item->id }}"
                                                                class="size-option is-disabled">
                                                                {{ $item->name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                            <h6 class="product-title product-title-2 d-block">Số lượng</h6>
                                            <div class="qty-box">
                                                <div class="input-group">
                                                    <span class="input-group-prepend">
                                                        <button type="button" class="btn quantity-left-minus"
                                                            data-type="minus">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </span>
                                                    <input type="text" name="quantity" class="form-control input-number"
                                                        id="quantity" value="1">
                                                    <span class="input-group-prepend">
                                                        <button type="button" class="btn quantity-right-plus"
                                                            data-type="plus">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="product-buttons">
                                            <button type="button" id="addToCartBtn"
                                                class="btn btn-solid hover-solid btn-animation">
                                                <i class="fa fa-shopping-cart"></i>
                                                <span>Thêm vào giỏ hàng</span>
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cart-list-section col-lg-3 col-md-4 mt-lg-5 mt-0" id="cartDropdownContainer">
                    {{-- Dữ liệu giỏ hàng sẽ được load vào đây bằng Ajax --}}
                </div>


                <div class="col-12">
                    <div class="cloth-review">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#desc" type="button">Mô tả</button>

                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#review"
                                    type="button">Bình luận</button>
                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="desc">
                                <div class="shipping-chart">
                                    <div class="part">
                                        <p class="font-light">
                                            {!! $product->description !!}
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="tab-pane fade" id="review">
                                <div class="row g-4">
                                    <div class="col-lg-4">
                                        <div class="customer-rating">
                                            <h2>Customer reviews</h2>
                                            <ul class="rating my-2 d-inline-block">
                                                <li>
                                                    <i class="fas fa-star theme-color"></i>
                                                </li>
                                                <li>
                                                    <i class="fas fa-star theme-color"></i>
                                                </li>
                                                <li>
                                                    <i class="fas fa-star theme-color"></i>
                                                </li>
                                                <li>
                                                    <i class="fas fa-star"></i>
                                                </li>
                                                <li>
                                                    <i class="fas fa-star"></i>
                                                </li>
                                            </ul>

                                            <div class="global-rating">
                                                <h5 class="font-light">82 Ratings</h5>
                                            </div>

                                            <ul class="rating-progess">
                                                <li>
                                                    <h5 class="me-3">5 Star</h5>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 78%"
                                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <h5 class="ms-3">78%</h5>
                                                </li>
                                                <li>
                                                    <h5 class="me-3">4 Star</h5>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 62%"
                                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <h5 class="ms-3">62%</h5>
                                                </li>
                                                <li>
                                                    <h5 class="me-3">3 Star</h5>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 44%"
                                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <h5 class="ms-3">44%</h5>
                                                </li>
                                                <li>
                                                    <h5 class="me-3">2 Star</h5>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 30%"
                                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <h5 class="ms-3">30%</h5>
                                                </li>
                                                <li>
                                                    <h5 class="me-3">1 Star</h5>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar" style="width: 18%"
                                                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <h5 class="ms-3">18%</h5>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-lg-8">
                                        <p class="d-inline-block me-2">Rating</p>
                                        <ul class="rating mb-3 d-inline-block">
                                            <li>
                                                <i class="fas fa-star theme-color"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star theme-color"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star theme-color"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star"></i>
                                            </li>
                                        </ul>
                                        <div class="review-box">
                                            <form class="row g-4">
                                                <div class="col-12 col-md-6">
                                                    <label class="mb-1" for="name">Name</label>
                                                    <input type="text" class="form-control" id="name"
                                                        placeholder="Enter your name" required>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <label class="mb-1" for="id">Email Address</label>
                                                    <input type="email" class="form-control" id="id"
                                                        placeholder="Email Address" required>
                                                </div>

                                                <div class="col-12">
                                                    <label class="mb-1" for="comments">Comments</label>
                                                    <textarea class="form-control" placeholder="Leave a comment here" id="comments" style="height: 100px" required></textarea>
                                                </div>

                                                <div class="col-12">
                                                    <button type="submit"
                                                        class="btn default-light-theme default-theme default-theme-2">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-4">
                                        <div class="customer-review-box">
                                            <h4>Customer Reviews</h4>

                                            <div class="customer-section">
                                                <div class="customer-profile">
                                                    <img src="assets/images/inner-page/review-image/1.jpg"
                                                        class="img-fluid blur-up lazyload" alt="">
                                                </div>

                                                <div class="customer-details">
                                                    <h5>Mike K</h5>
                                                    <ul class="rating my-2 d-inline-block">
                                                        <li>
                                                            <i class="fas fa-star theme-color"></i>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-star theme-color"></i>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-star theme-color"></i>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-star"></i>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-star"></i>
                                                        </li>
                                                    </ul>
                                                    <p class="font-light">I purchased my Tab S2 at Best Buy but I
                                                        wanted
                                                        to
                                                        share my thoughts on Amazon. I'm not going to go over specs
                                                        and
                                                        such
                                                        since you can read those in a hundred other places. Though I
                                                        will
                                                        say that the "new" version is preloaded with Marshmallow and
                                                        now
                                                        uses a Qualcomm octacore processor in place of the Exynos
                                                        that
                                                        shipped with the first gen.</p>

                                                    <p class="date-custo font-light">- Sep 08, 2021</p>
                                                </div>
                                            </div>
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
    <!-- Shop Section end -->

    <!-- product section start -->
    <section class="ratio_asos section-b-space overflow-hidden">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="mb-lg-4 mb-3">Sản phẩm cùng loại</h2>
                    <div class="product-wrapper product-style-2 slide-4 p-0 light-arrow bottom-space">
                        @foreach ($sameCateProducts as $item)
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
                                                <li>
                                                    <a href="wishlist.html" class="wishlist">
                                                        <i data-feather="heart"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-details">
                                        <div class="rating-details">
                                            <span class="font-light grid-content">{{ $item->Category->name }}</span>
                                        </div>
                                        <div class="main-price d-flex justify-content-between align-items-center">
                                            <a href="{{ route('product.detail', ['slug' => $item->slug]) }}"
                                                class="font-default text-decoration-none">
                                                <h6 class="fw-bold mb-0">{{ $item->name }}</h6>
                                            </a>
                                            <div>
                                                <span class="theme-color">
                                                    @if ($item->variants->min('price') == $item->variants->max('price'))
                                                        {{ number_format($item->variants->min('price')) }} Vnđ
                                                    @else
                                                        {{ number_format($item->variants->min('price')) }} -
                                                        {{ number_format($item->variants->max('price')) }} Vnđ
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

    {{-- <div class="sticky-bottom-cart" id="stickyCart">
        <div class="container">
            <div class="cart-content">
                <div class="product-image">
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid blur-up lazyload"
                        alt="{{ $product->name }}">
                    <div class="content">
                        <h5>{{ $product->name }}</h5>
                        <h6>
                            <span class="current-price">0₫</span>
                            <del class="original-price font-light d-none">0₫</del>
                            <span class="discount d-none">0% off</span>
                        </h6>
                    </div>
                </div>
                <div class="selection-section">
                    <div class="form-group mb-0">
                        <select id="inputState" class="form-control form-select">
                            <option disabled selected>Chọn màu...</option>
                            @foreach ($product->variants->unique('color_id') as $variant)
                                <option value="{{ $variant->color_id }}">{{ $variant->color->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <select id="input-state" class="form-control form-select">
                            <option selected disabled>Chọn size...</option>
                            <!-- Không hiển thị options ở đây, sẽ được cập nhật bằng JS -->
                        </select>
                    </div>
                </div>
                <div class="add-btn">
                    <button class="btn default-light-theme default-theme default-theme-2 outline-button add-to-cart-btn">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
    <script>
        const variants = @json($product->variants);
        const productName = @json($product->name);
    </script>
@endsection
