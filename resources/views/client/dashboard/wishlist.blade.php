@extends('client.dashboard.layouts.app')
@section('content')
    <!-- Cart Section Start -->

        <div class="col-lg-9">
            <div class="row">
                <div class="col-sm-12 table-responsive">
                    @if ($products->count() > 0)
                        <table class="table cart-table wishlist-table">
                            <thead>
                                <tr class="table-head">
                                    <th scope="col">hình ảnh</th>
                                    <th scope="col">tên sản phẩm</th>
                                    <th scope="col">hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <a href="{{ route('product.detail', ['slug' => $product->slug]) }}">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    class="blur-up lazyload" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('product.detail', $product->slug) }}"
                                                class="font-light">{{ $product->name }}</a>
                                            <div class="mobile-cart-content row">
                                                <div class="col">
                                                    <p>{{ $product->total_stock > 0 ? 'In Stock' : 'Out Of Stock' }}</p>
                                                </div>
                                                <div class="col">
                                                    <p class="fw-bold">${{ number_format($product->min_price, 2) }}</p>
                                                </div>
                                                <div class="col">
                                                    <h2 class="td-color">
                                                        <a href="javascript:void(0)" class="icon remove-favorite"
                                                            data-product-id="{{ $product->id }}">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    </h2>
                                                    <h2 class="td-color">
                                                        <a href="javascript:void(0)" class="icon add-to-cart-btn"
                                                            data-product-id="{{ $product->id }}"
                                                            data-product-name="{{ $product->name }}">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </a>
                                                    </h2>
                                                </div>
                                            </div>
                                        </td>


                                        <td>
                                            <a href="javascript:void(0)" class="icon remove-favorite"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-times"></i>
                                            </a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <h4>Danh sách yêu thích trống</h4>
                            <p>Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                            <a href="{{ route('client.welcome') }}" class="btn btn-solid-default">Tiếp tục mua sắm</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    <!-- Cart Section End -->


@endsection
