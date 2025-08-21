@extends('admin.layouts.app')
@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Order List</h5>
        </div>

        <!-- Table Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <div class="table-responsive table-desi">
                                    <table class="table table-striped all-package">
                                        <thead>
                                            <tr>
                                                <th>Mã Đơn hàng</th>
                                                <th>Sản phẩm</th>
                                                <th>Người đặt</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            @php
                                                $statusMap = [
                                                    'pending' => 'Chờ xác nhận',
                                                    'confirmed' => 'Đã xác nhận',
                                                    'packaging' => 'Đang đóng gói',
                                                    'shipped' => 'Đang giao',
                                                    'delivered' => 'Đã giao',
                                                    'cancelled' => 'Đã hủy',
                                                    'returned' => 'Đã trả hàng',
                                                ];
                                            @endphp
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        <a
                                                            href="{{ route('admin.order.detail', ['code' => $order->code]) }}">
                                                            {{ $order->code }}
                                                        </a>
                                                    </td>

                                                    <td>Jul 20, 2021</td>

                                                    <td>{{ $order->user->name }} - {{ $order->user->email }}</td>

                                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>

                                                    <td>
                                                        <div class="status-container">
                                                            <!-- Hiển thị ban đầu -->
                                                            <p class="status-display btn btn-sm
                                                                @if ($order->status === 'pending') btn-warning
                                                                @elseif($order->status === 'confirmed') btn-warning
                                                                @elseif($order->status === 'packaging') btn-primary
                                                                @elseif($order->status === 'shipped') btn-info
                                                                @elseif($order->status === 'delivered') btn-success
                                                                @elseif($order->status === 'cancelled') btn-danger
                                                                @elseif($order->status === 'returned') btn-dark
                                                                @else btn-light @endif"
                                                                data-order-id="{{ $order->id }}">
                                                                {{ $statusMap[$order->status] ?? $order->status }}
                                                            </p>

                                                            <!-- Dropdown select (ẩn ban đầu) -->
                                                            <select class="status-select form-select form-select-sm d-none"
                                                                data-order-id="{{ $order->id }}">
                                                                <option value="pending"
                                                                    @if ($order->status === 'pending') selected @endif>
                                                                    Chờ xác nhận</option>
                                                                <option value="confirmed"
                                                                    @if ($order->status === 'confirmed') selected @endif>
                                                                    Xác nhận</option>
                                                                <option value="packaging"
                                                                    @if ($order->status === 'packaging') selected @endif>
                                                                    Đóng gói</option>
                                                                <option value="shipped"
                                                                    @if ($order->status === 'shipped') selected @endif>
                                                                    Giao hàng</option>
                                                                <option value="cancelled"
                                                                    @if ($order->status === 'cancelled') selected @endif>Hủy
                                                                    đơn</option>
                                                            </select>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <a href="order-detail.html">
                                                                    <span class="lnr lnr-eye"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $orders->links('vendor.pagination.bootstrap-5') }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- Table End -->
    </div>
@endsection
