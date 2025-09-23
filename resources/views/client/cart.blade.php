@extends('client.layouts.app')

@section('content')
    <section class="cart-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 table-responsive mt-4">
                    <form action="{{ route('client.checkout') }}" method="GET" id="cart-form">
                        <input type="hidden" name="type" value="cart">

                        <table class="table cart-table">
                            <thead>
                                <tr class="table-head">
                                    <th scope="col">
                                        <input type="checkbox" id="check-all">
                                    </th>
                                    <th scope="col">Ảnh</th>
                                    <th scope="col">Tên sản phẩm</th>
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
                                            <input type="checkbox" name="cart_items[]" value="{{ $item->id }}"
                                                class="check-item">
                                        </td>
                                        <td>
                                            <a href="{{ route('product.detail', ['slug' => $product->slug]) }}">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    class="blur-up lazyload" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <p class="m-0">{{ $product->name }}</p>
                                            <span class="text-muted fs-7">Còn lại: {{ $variant->quantity }}</span>
                                        </td>
                                        <td>
                                            <p class="theme-color fs-6">{{ $item->color }}, {{ $item->size }}</p>
                                        </td>
                                        <td>
                                            <input type="number" class="update-quantity no-border"
                                                data-url="{{ route('cart.updateQuantity', ['id' => $item->id]) }}"
                                                value="{{ $item->quantity }}" min="1">
                                        </td>
                                        <td>
                                            <p class="m-0">{{ $item->quantity * $item->price }}</p>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-success remove-item"
                                                data-id="{{ $item->id }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="col-12 mt-md-5 mt-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <a href="javascript:void(0)"
                                    class="text-decoration-underline theme-color text-capitalize mb-2 mb-sm-0">
                                    Xóa toàn bộ sản phẩm
                                </a>

                                <div class="d-flex gap-2">
                                    <a href="index.html" class="btn btn-solid-default fw-bold">
                                        <i class="fas fa-arrow-left me-1"></i> Mua sắm
                                    </a>
                                    <button type="submit" class="btn btn-solid-default fw-bold">
                                        Thanh toán <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        // check/uncheck all
        document.getElementById("check-all").addEventListener("change", function() {
            const items = document.querySelectorAll(".check-item");
            items.forEach(el => el.checked = this.checked);
        });
    </script>
@endsection
