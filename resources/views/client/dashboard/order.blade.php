@extends('client.dashboard.layouts.app')

@section('content')
    <style>
        .truncate-text {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 200px;
            /* Bạn chỉnh theo layout */
            display: block;
        }
    </style>

    <div class="col-lg-9">
        <div class="table-dashboard dashboard wish-list-section" id="order">
            <div class="box-head mb-3">
                <h3>My Order</h3>
            </div>
            <div class="table-responsive">
                <table class="table cart-table">
                    <thead>
                        <tr class="table-head">
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Thông tin sản phẩm</th>
                            <th scope="col">Mã hóa đơn</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thanh toán</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @php
                                $detailCount = $order->orderDetails->count();
                                $first = true;
                            @endphp

                            @foreach ($order->orderDetails as $item)
                                <tr>
                                    <td>
                                        <a href="product-left-sidebar.html">
                                            <img src="{{ asset('storage/' . $item->productVariant->product->image) }}"
                                                alt="{{ $item->productVariant->product->name }}" class="blur-up lazyload">
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <p class="fs-6 m-0 truncate-text">
                                            {{ $item->productVariant->product->name }}
                                        </p>
                                        <p>{{ $item->color }}, size {{ $item->size }}, {{ $item->quantity }} cái,
                                            {{ number_format($item->price, 0, ',', '.') }}đ/cái</p>
                                    </td>

                                    @if ($first)
                                        <td rowspan="{{ $detailCount }}"><a
                                                href="{{ route('client.checkout.detail', ['code' => $order->code]) }}">#{{ $order->code }}</a>
                                        </td>
                                    @endif

                                    @if ($first)
                                        <td rowspan="{{ $detailCount }}">
                                            <span
                                                class="fw-bold
                                                    @if ($order->status === 'pending') text-warning
                                                    @elseif($order->status === 'confirmed') text-info
                                                    @elseif($order->status === 'packaging') text-primary
                                                    @elseif($order->status === 'shipped') text-secondary
                                                    @elseif($order->status === 'delivered') text-success
                                                    @elseif($order->status === 'cancelled') text-danger
                                                    @elseif($order->status === 'returned') text-dark
                                                    @else text-muted @endif">
                                                {{ getStatusName($order->status) }}
                                            </span>
                                        </td>
                                        <td rowspan="{{ $detailCount }}">
                                            @if ($order->payment->method === 'COD')
                                                <span class="badge bg-secondary">Thanh toán khi nhận hàng</span>
                                            @elseif($order->payment->method === 'VNPay')
                                                @if ($order->payment->status === 'failed')
                                                    <span class="badge bg-danger">Lỗi thanh toán</span>
                                                @else
                                                    <span class="badge bg-success">Thanh toán qua VNPay</span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger">Chưa thanh toán</span>
                                            @endif
                                        </td>
                                        <td rowspan="{{ $detailCount }}">
                                            <p class="theme-color fs-6">
                                                {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                            </p>
                                        </td>
                                    @endif

                                    @if ($first)
                                        <td rowspan="{{ $detailCount }}">
                                            @if ($order->status === 'shipped')
                                                <button type="button" class="btn btn-success btn-receive-order"
                                                    data-order-id="{{ $order->id }}">Nhận đơn</button>
                                            @elseif ($order->status === 'delivered')
                                                <button class="btn btn-solid-default btn-sm fw-bold ms-auto btn-show-order"
                                                    data-order-code="{{ $order->code }}">
                                                    Đánh giá ngay
                                                </button>
                                            @elseif($order->status === 'cancelled')
                                                <form
                                                    action="{{ route('client.checkout.rebuy', ['order' => $order->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning"> Mua lại</button>
                                                </form>
                                            @elseif($order->payment->status == 'failed')
                                                <form
                                                    action="{{ route('client.checkout.edit', ['code' => $order->code]) }}"
                                                    method="GET">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning"> Thanh toán lại</button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-danger btn-cancel-order"
                                                    data-order-id="{{ $order->id }}">Hủy
                                                    đơn</button>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                                @php $first = false; @endphp
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $orders->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="addComment">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('client.comment.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Nội dung sẽ load bằng AJAX -->
                    </div>
                    <div class="modal-footer pt-0 text-end d-block">
                        <button type="button" class="btn bg-secondary text-white rounded-1"
                            data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-solid-default rounded-1">Gửi đánh giá</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
