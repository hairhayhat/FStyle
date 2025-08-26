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
        <div class="box-head mb-3">
            <h3>Danh sách đơn hàng</h3>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <form action="" method="GET" id="filterForm" class="d-flex align-items-center gap-2 flex-wrap">
                <input type="hidden" name="status" value="{{ request('status', 'pending') }}">

                <select name="sort" class="form-select form-select-sm w-auto">
                    <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                </select>

                <select name="per_page" class="form-select form-select-sm w-auto">
                    <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 / trang</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 / trang</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 / trang</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / trang</option>
                </select>

                <div class="d-flex align-items-center gap-2">
                    <label>
                        <input type="radio" name="payment" value="" {{ request('payment') == '' ? 'checked' : '' }}>
                        Tất cả
                    </label>
                    <label>
                        <input type="radio" name="payment" value="vnpay"
                            {{ request('payment') == 'vnpay' ? 'checked' : '' }}> VNPay
                    </label>
                    <label>
                        <input type="radio" name="payment" value="cod"
                            {{ request('payment') == 'cod' ? 'checked' : '' }}> COD
                    </label>
                </div>
            </form>

            <div class="status-filter d-flex flex-wrap gap-2 mt-3">
                <button type="button" class="btn btn-solid-default btn-status" data-status="pending">
                    Chờ Xác Nhận <span class="badge">{{ $statusCounts['pending'] }}</span>
                </button>
                <button type="button" class="btn btn-solid-default btn-status" data-status="confirmed">
                    Đã Xác Nhận <span class="badge">{{ $statusCounts['confirmed'] }}</span>
                </button>
                <button type="button" class="btn btn-solid-default btn-status" data-status="packaging">
                    Đang Đóng Gói <span class="badge">{{ $statusCounts['packaging'] }}</span>
                </button>
                <button type="button" class="btn btn-solid-default btn-status" data-status="shipped">
                    Đang Giao <span class="badge">{{ $statusCounts['shipped'] }}</span>
                </button>
                <button type="button" class="btn btn-solid-default btn-status" data-status="delivered">
                    Đã Giao <span class="badge">{{ $statusCounts['delivered'] }}</span>
                </button>
                <button type="button" class="btn btn-solid-default btn-status" data-status="cancelled">
                    Đã Hủy <span class="badge">{{ $statusCounts['cancelled'] }}</span>
                </button>
            </div>

        </div>

        <div id="orderTableWrapper" data-url="{{ route('client.checkout.index') }}">
            @include('client.partials.orders-table', ['orders' => $orders])
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
