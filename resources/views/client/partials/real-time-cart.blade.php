<div class="category-option">
    <div class="button-close mb-3">
        <button class="btn p-0"><i data-feather="arrow-left"></i> Close</button>
    </div>

    <div class="accordion category-name" id="accordionExample">
        <div class="accordion-item category-rating">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree">
                    Giỏ hàng - {{ number_format($total, 0, ',', '.') }} đ
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree"
                data-bs-parent="#accordionExample">
                <div class="accordion-body category-scroll-both">
                    <ul class="category-list p-0 m-0 list-unstyled">
                        @foreach ($cartItems as $item)
                            @php
                                $variant = $item->productVariant;
                                $product = $variant?->product;
                            @endphp

                            <li class="mb-3 d-flex align-items-start gap-2">
                                <button class="btn btn-sm btn-outline-danger btn-remove-item "
                                    data-id="{{ $item->id }}" style="padding: 2px 5px;">x</button>

                                <img src="{{ asset('storage/' . $product?->image) }}" alt="{{ $product?->name }}"
                                    width="50" height="50" class="rounded" style="object-fit: cover">

                                <div class="cart-info flex-grow-1">
                                    <strong class="d-block text-truncate" title="{{ $product?->name }}">
                                        {{ $product?->name }}
                                    </strong>
                                    <small class="text-muted">
                                        Màu: {{ $item->color }}, Size: {{ $item->size }}, Số lượng:
                                        {{ $item->quantity }}
                                    </small><br>
                                    <small class="fw-semibold text-primary">
                                        {{ number_format($item->price, 0, ',', '.') }} đ
                                    </small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="product-buttons text-center mt-3">
                <a href="{{ route('client.checkout') }}" id="addToCartBtn"
                    class="btn btn-solid hover-solid w-100 d-flex align-items-center justify-content-center gap-2 py-2 rounded-3 shadow-sm">
                    <i class="fa fa-credit-card"></i>
                    <span class="fw-semibold">Thanh toán ngay</span>
                </a>
            </div>

        </div>
    </div>
</div>
