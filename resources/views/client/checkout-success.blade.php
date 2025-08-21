@extends('client.layouts.app')

@section('content')
    <section class="pt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 p-0">
                    <div class="success-icon">
                        <div class="main-container">
                            <div class="check-container">
                                <div class="check-background">
                                    <svg viewBox="0 0 65 51" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7 25L27.3077 44L58.5 7" stroke="white" stroke-width="13"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="check-shadow"></div>
                            </div>
                        </div>

                        <div class="success-contain">
                            <h4>Đặt hàng thành công</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Order Success Section End -->

    <!-- Oder Details Section Start -->
    <section class="section-b-space cart-section order-details-table">
        <div class="container">
            <div class="title title1 title-effect mb-1 title-left">
                <h2 class="mb-3">Chi tiết hóa đơn</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="col-sm-12 table-responsive">
                        <table class="table cart-table table-borderless">
                            @php
                                $totalProduct = 0;
                            @endphp
                            @foreach ($order->orderDetails as $item)
                                <tbody>
                                    <tr class="table-order">
                                        <td>
                                            <a
                                                href="{{ route('product.detail', ['slug' => $item->productVariant->product->slug]) }}">
                                                <img src="{{ asset('storage/' . $item->productVariant->product->image) }}"
                                                    alt="{{ $item->productVariant->product->name }}"
                                                    class="blur-up lazyload">
                                            </a>
                                        </td>
                                        <td>
                                            <p class="truncate-text">{{ $item->productVariant->product->name }}</p>
                                            <h5>{{ $item->productVariant->product->category->name }}</h5>
                                        </td>
                                        <td>
                                            <p>Chi tiết</p>
                                            <h5>{{ $item->quantity }} cái {{ $item->color }}, size {{ $item->size }}</h5>
                                        </td>
                                        <td>
                                            <p>Giá</p>
                                            <h5>{{ number_format($item->price, 0, ',', '.') }}đ/cái</h5>
                                        </td>
                                    </tr>
                                </tbody>

                                @php
                                    $totalProduct += $item->price * $item->quantity;
                                @endphp
                            @endforeach
                            <tfoot>
                                <tr class="table-order">
                                    <td colspan="3">
                                        <h5 class="font-light">Tổng tiền sản phẩm: </h5>
                                    </td>
                                    <td>
                                        <h4>{{ number_format($totalProduct, 0, ',', '.') }}đ</h4>
                                    </td>
                                </tr>

                                <tr class="table-order">
                                    <td colspan="3">
                                        <h5 class="font-light">Voucher: </h5>
                                    </td>
                                    <td>
                                        <h4>-{{ number_format($order->orderVoucher->discount ?? 0, 0, ',', '.') }}đ</h4>
                                    </td>
                                </tr>

                                <tr class="table-order">
                                    <td colspan="3">
                                        <h4 class="theme-color fw-bold">Tổng tiền :</h4>
                                    </td>
                                    <td>
                                        <h4 class="theme-color fw-bold">
                                            {{ number_format($order->total_amount, 0, ',', '.') }}đ</h4>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="order-success">
                        <div class="row g-4">
                            <div class="col-sm-6">
                                <h4>Tóm tắt</h4>
                                <ul class="order-details">
                                    <li>Mã hóa đơn: {{ $order->code }}</li>
                                    <li>Ngày đặt hàng: {{ $order->created_at->format('H:i:s, d-m-Y') }}</li>
                                    <li>Trạng thái: {{ getStatusName($order->status) }}</li>
                                    <li>Tổng tiền: {{ number_format($order->total_amount, 0, ',', '.') }}đ</li>
                                </ul>
                            </div>

                            <div class="col-sm-6">
                                <h4>Thông tin người nhận</h4>
                                <ul class="order-details">
                                    <li>{{ $order->shippingAddress->full_name }}</li>
                                    <li>{{ $order->shippingAddress->phone }}</li>
                                    <li>{{ $order->shippingAddress->address }}</li>
                                </ul>
                            </div>

                            <div class="col-sm-6">
                                <div class="payment-mode">
                                    <h4>Hình thức thanh toán</h4>
                                    <p>{{ $order->payment->method }}</p>

                                    @if ($order->payment->method === 'VNPay' && $order->payment->gateway_data)
                                        @php
                                            $vnpayData = $order->payment->gateway_data;
                                        @endphp

                                        <ul class="order-details">
                                            <li>Số tiền: {{ number_format($vnpayData['vnp_Amount'] / 100, 0, ',', '.') }}₫
                                            </li>
                                            <li>Mã đơn hàng: {{ $vnpayData['vnp_TxnRef'] }}</li>
                                            <li>Ngày thanh toán:
                                                {{ \Carbon\Carbon::createFromFormat('YmdHis', $vnpayData['vnp_PayDate'])->format('d/m/Y H:i:s') }}
                                            </li>
                                            <li>Mã ngân hàng: {{ $vnpayData['vnp_BankCode'] }}</li>
                                            <li>Loại thẻ: {{ $vnpayData['vnp_CardType'] }}</li>
                                            <li>Mã giao dịch ngân hàng: {{ $vnpayData['vnp_BankTranNo'] }}</li>
                                            <li>Mã giao dịch VNPay: {{ $vnpayData['vnp_TransactionNo'] }}</li>
                                            <li>Trạng thái:
                                                {{ $vnpayData['vnp_TransactionStatus'] === '00' ? 'Thành công' : 'Thất bại' }}
                                            </li>
                                        </ul>
                                    @endif
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <h4>voucher</h4>
                                <ul class="order-details">
                                    <li>Voucher code: {{ $order->orderVoucher->code ?? 0 }}</li>
                                    <li>Giảm:
                                        {{ number_format($order->orderVoucher->discount ?? 0, 0, ',', '.') }}đ</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="product-buttons d-flex justify-content-end mt-4">
                        <a href="{{ route('client.checkout.index') }}" class="btn btn-solid hover-solid btn-animation">
                            Danh sách hóa đơn <i class="fa fa-arrow-right ms-2"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>


    </section>
@endsection
