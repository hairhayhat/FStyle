@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header title-header-block package-card">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Hóa đơn #{{ $order->code }}</h5>
            </div>
            <div class="card-order-section">
                <ul>
                    <li>{{ $order->created_at->format('H:i:s, d-m-Y') }}</li>
                    <li>{{ $order->orderDetails->count() }} sản phẩm</li>
                    <li>Tổng tiền {{ number_format($order->total_amount, 0, ',', '.') }}đ</li>
                </ul>
            </div>
        </div>

        <!-- tracking table start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="bg-inner cart-section order-details-table">
                                <div class="row g-4">
                                    <div class="col-xl-8">
                                        <div class="table-responsive table-details">
                                            <table class="table cart-table table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">Sản phẩm</th>
                                                        <th class="text-end" colspan="2">
                                                            <a href="javascript:void(0)" class="theme-color"></a>
                                                        </th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @php
                                                        $totalProduct = 0;
                                                    @endphp
                                                    @foreach ($order->orderDetails as $item)
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
                                                                <p>{{ $item->productVariant->product->category->name }}
                                                                </p>
                                                                <h5>{{ $item->productVariant->product->name }}</h5>
                                                            </td>
                                                            <td>
                                                                <p>Thông tin chi tiết</p>
                                                                <h5>{{ $item->quantity }} cái {{ $item->color }},
                                                                    size
                                                                    {{ $item->size }}</h5>
                                                            </td>
                                                            <td>
                                                                <p>Giá</p>
                                                                <h5>{{ number_format($item->price, 0, ',', '.') }}đ/cái
                                                                </h5>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $totalProduct += $item->price * $item->quantity;
                                                        @endphp
                                                    @endforeach
                                                </tbody>

                                                <tfoot>
                                                    <tr class="table-order">
                                                        <td colspan="3">
                                                            <h5>Tổng phụ :</h5>
                                                        </td>
                                                        <td>
                                                            <h4>{{ number_format($totalProduct, 0, ',', '.') }}đ</h4>
                                                        </td>
                                                    </tr>

                                                    <tr class="table-order">
                                                        <td colspan="3">
                                                            <h5>Giảm giá :</h5>
                                                        </td>
                                                        <td>
                                                            <h4>-{{ number_format($order->orderVoucher->discount ?? 0, 0, ',', '.') }}đ
                                                            </h4>
                                                        </td>
                                                    </tr>

                                                    <tr class="table-order">
                                                        <td colspan="3">
                                                            <h4 class="theme-color fw-bold">Tổng :</h4>
                                                        </td>
                                                        <td>
                                                            <h4 class="theme-color fw-bold">
                                                                {{ number_format($order->total_amount, 0, ',', '.') }}đ
                                                            </h4>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-xl-4">
                                        <div class="order-success">
                                            <div class="row g-4">
                                                <h4>Tóm tắt</h4>
                                                <ul class="order-details">
                                                    <li>ID hóa đơn: {{ $order->code }}</li>
                                                    <li>Ngày đặt hàng: {{ $order->created_at->format('H:i:s, d-m-Y') }}
                                                    </li>
                                                    <li>Trạng thái: <strong> {{ getStatusName($order->status) }}</strong>
                                                    </li>
                                                    @if ($order->status == 'cancelled')
                                                        <li>Lý do hủy: {{ $order->note }}</li>
                                                    @endif
                                                    <li>Tổng hóa đơn:
                                                        {{ number_format($order->total_amount, 0, ',', '.') }}đ</li>
                                                </ul>

                                                <h4>Voucher áp dụng</h4>
                                                @if ($order->orderVoucher)
                                                    <ul class="order-details">
                                                        <li>
                                                            ID voucher: {{ $order->orderVoucher->code ?? 0 }} -
                                                            {{ $order->orderVoucher->voucher->type == 'percent'
                                                                ? 'Giảm ' . intval($order->orderVoucher->voucher->value) . '%'
                                                                : 'Giảm cố định ' . number_format($order->orderVoucher->voucher->value, 0, ',', '.') . 'đ' }}
                                                        </li>

                                                        <li>Ngày áp dụng:
                                                            {{ $order->orderVoucher->applied_at->format('H:i:s, d-m-Y') }}
                                                        </li>
                                                        <li>Giảm:
                                                            {{ number_format($order->orderVoucher->discount ?? 0, 0, ',', '.') }}đ
                                                        </li>
                                                    </ul>
                                                @else
                                                    <ul class="order-details">
                                                        <li>
                                                            Không có voucher áp dụng
                                                        </li>
                                                    </ul>
                                                @endif


                                                <h4>Thông tin người nhận</h4>
                                                <ul class="order-details">
                                                    <li>{{ $order->shippingAddress->full_name }}</li>
                                                    <li>{{ $order->shippingAddress->phone }}</li>
                                                    <li>{{ $order->shippingAddress->address }}</li>
                                                </ul>

                                                <div class="payment-mode">
                                                    <h4>Hình thức thanh toán</h4>
                                                    <p>{{ $order->payment->method }}</p>

                                                    @if ($order->payment->method === 'VNPay' && $order->payment->gateway_data)
                                                        @php
                                                            $vnpayData = $order->payment->gateway_data;
                                                        @endphp

                                                        <ul class="order-details">
                                                            <li>Số tiền:
                                                                {{ number_format($vnpayData['vnp_Amount'] / 100, 0, ',', '.') }}₫
                                                            </li>
                                                            <li>Mã đơn hàng: {{ $vnpayData['vnp_TxnRef'] }}</li>
                                                            <li>Ngày thanh toán:
                                                                {{ \Carbon\Carbon::createFromFormat('YmdHis', $vnpayData['vnp_PayDate'])->format('d/m/Y H:i:s') }}
                                                            </li>
                                                            <li>Mã ngân hàng: {{ $vnpayData['vnp_BankCode'] }}</li>
                                                            <li>Loại thẻ: {{ $vnpayData['vnp_CardType'] }}</li>
                                                            <li>Mã giao dịch ngân hàng: {{ $vnpayData['vnp_BankTranNo'] }}
                                                            </li>
                                                            <li>Mã giao dịch VNPay: {{ $vnpayData['vnp_TransactionNo'] }}
                                                            </li>
                                                            <li>Trạng thái:
                                                                {{ $vnpayData['vnp_TransactionStatus'] === '00' ? 'Thành công' : 'Thất bại' }}
                                                            </li>
                                                        </ul>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- section end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- tracking table end -->
    </div>
@endsection
