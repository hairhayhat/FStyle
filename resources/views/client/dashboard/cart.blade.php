@extends('client.dashboard.layouts.app')

@section('content')
    <div class="col-lg-9">
        <div class="table-dashboard dashboard wish-list-section" id="wishlist">
            <div class="box-head mb-3">
                <h3>Giỏ hàng</h3>
            </div>
            <div class="table-responsive">
                <table class="table cart-table">
                    <thead>
                        <tr class="table-head">
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tên sản phẩm</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Thông tin</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Giá tiền</th>
                            <th scope="col">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            @php
                                $variant = $item->productVariant;
                                $product = $variant?->product;
                            @endphp

                            <tr>
                                <td>
                                    <a href="product-left-sidebar.html">
                                        <img src="{{ asset('storage/' . $product->image) }}" class="blur-up lazyload"
                                            alt="">
                                    </a>
                                </td>
                                <td>
                                    <p class="m-0">{{ $product->name }}</p>
                                </td>
                                <td>
                                    <p class="fs-6 m-0">{{ $product->category->name }}</p>
                                </td>
                                <td>
                                    <p class="theme-color fs-6">{{ $item->color }}, {{ $item->size }}</p>
                                </td>
                                <td>
                                    <p class="m-0">{{ $item->quantity }}</p>
                                </td>
                                <td>
                                    <p class="m-0">{{ $item->quantity * $item->price }}</p>
                                </td>
                                <td>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="cart-checkout-section mt-4">
            <a href="" class="btn btn-solid-default btn-lg w-100 mt-3 fw-bold">
                Tiến hành thanh toán (<span>{{ number_format($total, 0, ',', '.') }}₫</span>) <i
                    class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

    </div>
@endsection
