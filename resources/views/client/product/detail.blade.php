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
                                            <button type="button"
                                                class="btn btn-solid hover-solid btn-animation cart-action-btn"
                                                data-action="add">
                                                <i class="fa fa-shopping-cart"></i>
                                                <span>Thêm vào giỏ hàng</span>
                                            </button>

                                            <button type="button"
                                                class="btn btn-solid hover-solid btn-animation cart-action-btn"
                                                data-action="buy">
                                                <i class="fa fa-bolt"></i>
                                                <span>Mua ngay</span>
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

                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#review" type="button">Bình luận</button>
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
                                            <h2>Đánh giá của khách hàng</h2>

                                            <!-- Sao trung bình -->
                                            <ul class="rating my-2 d-inline-block">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <li><i
                                                            class="fas fa-star {{ $i <= round($averageRating) ? 'theme-color' : '' }}"></i>
                                                    </li>
                                                @endfor
                                            </ul>

                                            <div class="global-rating">
                                                <h5 class="font-light">{{ $totalRatings }} Đánh giá</h5>
                                            </div>

                                            <!-- % phân bố từng sao -->
                                            <ul class="rating-progess">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <li>
                                                        <h5 class="me-3">{{ $i }} Star</h5>
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{ $ratingPercentages[$i] }}%"
                                                                aria-valuenow="{{ $ratingPercentages[$i] }}"
                                                                aria-valuemin="0" aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <h5 class="ms-3">{{ $ratingPercentages[$i] }}%</h5>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="customer-review-box">
                                            <h4>Bình luận của khách hàng</h4>

                                            @forelse($comments as $comment)
                                                <div class="customer-section mb-4">
                                                    <div class="customer-profile">
                                                        <img src="{{ $comment->user->avatar ?? 'assets/images/default-avatar.jpg' }}"
                                                            class="img-fluid blur-up lazyload" alt="">
                                                    </div>

                                                    <div class="customer-details">
                                                        <h5>{{ $comment->user->name ?? $comment->name }}</h5>

                                                        <ul class="rating my-2 d-inline-block">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <li>
                                                                    <i
                                                                        class="fas fa-star {{ $i <= $comment->rating ? 'theme-color' : '' }}"></i>
                                                                </li>
                                                            @endfor
                                                        </ul>

                                                        <p class="font-light">{{ $comment->content }}</p>

                                                        <!-- Hiển thị ảnh/video của comment -->
                                                        @if ($comment->media->count())
                                                            <div class="comment-media d-flex flex-wrap mt-2">
                                                                @foreach ($comment->media as $media)
                                                                    @if (Str::endsWith($media->file_path, ['.mp4', '.webm', '.ogg']))
                                                                        <video width="120" height="80" controls
                                                                            class="me-2 mb-2">
                                                                            <source src="{{ asset($media->file_path) }}"
                                                                                type="video/mp4">
                                                                            Your browser does not support the video tag.
                                                                        </video>
                                                                    @else
                                                                        <img src="{{ asset($media->file_path) }}"
                                                                            class="img-fluid me-2 mb-2"
                                                                            style="width:120px; height:80px; object-fit:cover;"
                                                                            alt="">
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif

                                                        <p class="date-custo font-light">-
                                                            {{ $comment->created_at->format('M d, Y') }}</p>
                                                    </div>
                                                </div>
                                            @empty
                                                <p>Chưa có đánh giá nào.</p>
                                            @endforelse
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
