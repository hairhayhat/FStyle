<div
    class="row g-sm-4 g-3 row-cols-lg-4 row-cols-md-3 row-cols-2 mt-1 custom-gy-5 product-style-2 ratio_asos product-list">
    @forelse ($products as $item)
        <div class="col">
            <div class="product-box">
                <div class="img-wrapper">
                    <div class="front">
                        <a href="{{ route('product.detail', ['slug' => $item->slug]) }}">
                            <img src="{{ asset('storage/' . $item->image) }}" class="blur-up lazyload" alt="">
                        </a>
                    </div>
                    <div class="cart-wrap">
                        <ul>
                            <li>
                                <a href="javascript:void(0)" class="addtocart-btn" data-bs-toggle="modal"
                                    data-bs-target="#addtocart">
                                    <i data-feather="shopping-bag"></i>
                                </a>
                            </li>
                            <li>
                                <button type="button" class="quick-view-btn"
                                    data-url="{{ route('product.detail.api', ['slug' => $item->slug]) }}">
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
                                    <button type="button" class="favorite-toggle" data-slug="{{ $item->slug }}"
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
                                    <li><i class="fas fa-star fa-xs theme-color"></i></li>
                                @elseif ($i == ceil($avgRating) && $avgRating - floor($avgRating) >= 0.5)
                                    <li><i class="fas fa-star-half-alt fa-xs theme-color"></i></li>
                                @else
                                    <li><i class="fas fa-star fa-xs"></i></li>
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
    @empty
        <div class="col-12 d-flex justify-content-center align-items-center" style="min-height:200px;">
            <h4 class="text-muted text-center">Không tìm thấy sản phẩm nào phù hợp.</h4>
        </div>
    @endforelse
</div>

<div class="mt-3 product-pagination">
    {{ $products->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
</div>
