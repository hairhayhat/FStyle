@extends('admin.layouts.app')
@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Danh sách đơn hàng</h5>
        </div>

        <!-- Table Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                <form action="" method="GET" id="filterForm"
                                    class="d-flex align-items-center gap-2 flex-wrap">
                                    <input type="hidden" name="status" value="{{ request('status', 'pending') }}">

                                    <select name="sort" class="form-select form-select-sm w-auto">
                                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Mới
                                            nhất</option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất
                                        </option>
                                    </select>

                                    <select name="per_page" class="form-select form-select-sm w-auto">
                                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 / trang
                                        </option>
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 / trang
                                        </option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 / trang
                                        </option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / trang
                                        </option>
                                    </select>

                                    <div class="d-flex align-items-center gap-2">
                                        <label>
                                            <input type="radio" name="payment" value=""
                                                {{ request('payment') == '' ? 'checked' : '' }}>
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
                                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                        data-status="pending">
                                        Chờ Xác Nhận <span class="badge">{{ $statusCounts['pending'] }}</span>
                                    </button>
                                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                        data-status="confirmed">
                                        Đã Xác Nhận <span class="badge">{{ $statusCounts['confirmed'] }}</span>
                                    </button>
                                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                        data-status="packaging">
                                        Đang Đóng Gói <span class="badge">{{ $statusCounts['packaging'] }}</span>
                                    </button>
                                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                        data-status="shipped">
                                        Đang Giao <span class="badge">{{ $statusCounts['shipped'] }}</span>
                                    </button>
                                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                        data-status="delivered">
                                        Đã Giao <span class="badge">{{ $statusCounts['delivered'] }}</span>
                                    </button>
                                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                        data-status="rated">
                                        Đã đánh giá <span class="badge">{{ $statusCounts['rated'] }}</span>
                                    </button>
                                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold btn-status"
                                        data-status="cancelled">
                                        Đã Hủy <span class="badge">{{ $statusCounts['cancelled'] }}</span>
                                    </button>
                                </div>

                            </div>

                            <div id="orderTableWrapper" data-url="{{ route('admin.order.index') }}">
                                @include('admin.partials.table-orders', ['orders' => $orders])
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
