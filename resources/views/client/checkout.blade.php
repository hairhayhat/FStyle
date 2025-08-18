@extends('client.layouts.app')

@section('content')
    <section class="section-b-space">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <h3 class="mb-3">Địa chỉ nhận hàng</h3>
                    <form class="needs-validation" action="{{ route('client.checkout.store') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="voucher_code" id="voucher_code_input">
                        <div class="save-details-box" id="addressList">
                            <div class="row g-3">
                                @foreach ($addresses as $item)
                                    <div class="col-xl-4 col-md-6">
                                        <label class="save-details">
                                            <input type="radio" name="selected_address" value="{{ $item->id }}"
                                                @if ($item->is_default) checked @endif required>

                                            <span class="badge-nickname">{{ $item->nickname }}</span>

                                            <div class="save-name"></div>

                                            <div class="save-address">
                                                <p><strong>Địa chỉ:</strong> {{ $item->address }}</p>
                                            </div>
                                            <div class="mobile">
                                                <p>Họ và tên: {{ $item->full_name }}</p>
                                            </div>
                                            <div class="mobile">
                                                <p>Sđt: {{ $item->phone }}</p>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-solid-default mt-4" data-bs-toggle="modal"
                                data-bs-target="#addAddress"><i class="fas fa-plus"></i> Thêm địa chỉ mới</button>
                        </div>

                        <hr class="my-lg-5 my-4">

                        <h3 class="mb-3">Hình thức thanh toán
                            <small
                                style="color: #dc3545; font-weight: 600; font-size: 0.9rem; display: block; margin-top: 0.3rem;">
                                Nếu bạn không lựa chọn hình thức thanh toán, hệ thống sẽ tự động chọn COD
                            </small>
                        </h3>

                        <div class="d-block my-3">
                            <div class="form-check custome-radio-box">
                                <input class="form-check-input" type="radio" name="payment_method" id="credit"
                                    value="vnpay">
                                <label class="form-check-label" for="credit">
                                    <img src="{{ asset('client/assets/images/logo/vnpay.jpg') }}" alt="vnpay"
                                        style="height:100px; width:auto;">
                                </label>
                            </div>

                            <div class="form-check custome-radio-box">
                                <input class="form-check-input" type="radio" name="payment_method" id="momo"
                                    value="momo">
                                <label class="form-check-label" for="momo">
                                    <img src="{{ asset('client/assets/images/logo/momo.jpg') }}" alt="Momo"
                                        style="height:100px; width:auto;">
                                </label>
                            </div>

                            <div class="form-check custome-radio-box">
                                <input class="form-check-input" type="radio" name="payment_method" id="zalopay"
                                    value="zalopay">
                                <label class="form-check-label" for="zalopay">
                                    <img src="{{ asset('client/assets/images/logo/zalopay.jpg') }}" alt="Zalo Pay"
                                        style="height:100px; width:auto;">
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-solid-default mt-4" type="submit">Đặt hàng</button>
                    </form>

                </div>

                <div class="col-lg-4">
                    <div class="your-cart-box">
                        <h3 class="mb-3 d-flex text-capitalize">Giỏ hàng<span
                                class="badge bg-theme new-badge rounded-pill ms-auto bg-dark">{{ count($cartItems) }}</span>
                        </h3>
                        <ul class="list-group mb-3">
                            @foreach ($cartItems as $item)
                                @php
                                    if (is_array($item)) {
                                        $variant = $item['productVariant'];
                                        $product = $variant->product ?? null;
                                        $quantity = $item['quantity'];
                                        $price = $variant->sale_price ?? 0;
                                        $color = $item['color'] ?? null;
                                        $size = $item['size'] ?? null;
                                    } else {
                                        $variant = $item->productVariant;
                                        $product = $variant?->product;
                                        $quantity = $item->quantity;
                                        $price = $item->price;
                                        $color = $item->color;
                                        $size = $item->size;
                                    }
                                @endphp

                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <div>
                                        <h6 class="my-0">{{ $product?->name }}</h6>
                                        <small>{{ $color }}, Size {{ $size }}, {{ $quantity }}
                                            cái</small>
                                    </div>
                                    <span>{{ number_format($quantity * $price) }}đ/cái</span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Mã giảm giá</h6>
                                    <small></small>
                                </div>
                                <span id="voucher-discount">-0đ</span>
                            </li>

                            <li class="list-group-item d-flex lh-condensed justify-content-between">
                                <span class="fw-bold">Tổng (Vnđ)</span>
                                <strong id="cart-total-display" data-total="{{ $total }}">
                                    {{ number_format($total) }}đ
                                </strong>
                            </li>
                        </ul>

                        <form class="card border-0">
                            <div class="input-group custome-imput-group">
                                <select class="form-control" id="voucher-select">
                                    <option value="">Chọn mã khuyến mãi</option>
                                    @foreach ($vouchers as $item)
                                        @if ($item->type == 'percent')
                                            <option value="{{ $item->code }}" data-type="percent"
                                                data-value="{{ (int) $item->value }}"
                                                data-max="{{ $item->max_discount_amount }}"
                                                data-min="{{ $item->min_order_amount }}">
                                                Giảm {{ (int) $item->value }}%. Tối đa
                                                {{ number_format($item->max_discount_amount) }}đ
                                                cho đơn hàng trên {{ number_format($item->min_order_amount) }}đ
                                            </option>
                                        @else
                                            <option value="{{ $item->code }}" data-type="fixed"
                                                data-value="{{ $item->value }}"
                                                data-max="{{ $item->max_discount_amount }}"
                                                data-min="{{ $item->min_order_amount }}">
                                                Giảm {{ number_format($item->value, 0) }}đ. Tối đa
                                                {{ number_format($item->max_discount_amount) }}đ
                                                cho đơn hàng trên {{ number_format($item->min_order_amount) }}đ
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade add-address-modal" id="addAddress" role="dialog" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" id="addAddressForm" action="{{ route('client.address.create') }}"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="full_name" class="form-label font-light">Họ và tên</label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                id="full_name" name="full_name" required>
                            @error('full_name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label font-light">Số điện thoại</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" required>
                            @error('phone')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nickname" class="form-label font-light">Tên địa chỉ</label>
                            <input type="text" class="form-control @error('nickname') is-invalid @enderror"
                                id="nickname" name="nickname" required>
                            @error('nickname')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label font-light">Địa chỉ</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2"
                                required></textarea>
                            @error('address')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_default" name="is_default"
                                value="0">
                            <label class="form-check-label font-light" for="is_default">Đặt làm mặc định ?</label>
                        </div>

                    </div>

                    <div class="modal-footer pt-0 text-end d-block">
                        <button type="button" class="btn bg-secondary text-white rounded-1"
                            data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-solid-default rounded-1">Xong</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
