@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="title-header d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Thống kê</h5>

                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2">
                        @php
                            use Carbon\Carbon;
                        @endphp

                        <div id="date-range" class="d-flex align-items-center gap-2" style="display: none;">
                            <input type="date" name="from_date"
                                class="form-control form-control-sm shadow-sm rounded-pill px-3"
                                value="{{ request('from_date') ?? Carbon::now()->subYear()->format('Y-m-d') }}">
                            <span class="fw-bold">-</span>
                            <input type="date" name="to_date"
                                class="form-control form-control-sm shadow-sm rounded-pill px-3"
                                value="{{ request('to_date') ?? Carbon::now()->format('Y-m-d') }}">
                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Lọc</button>
                        </div>

                    </form>
                </div>
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="b-b-primary border-5 border-0 card o-hidden">
                        <div class="custome-1-bg b-r-4 card-body">
                            <div class="media align-items-center static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Tổng doanh thu thuần</span>
                                    <h4 class="mb-0 counter"> {{ number_format($totalEarnings ?? 0) }}₫
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i data-feather="database"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="b-b-danger border-5 border-0 card o-hidden">
                        <div class=" custome-2-bg  b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Tổng đơn hàng</span>
                                    <h4 class="mb-0 counter">{{ $totalBooking }}
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i data-feather="shopping-bag"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="b-b-secondary border-5 border-0  card o-hidden">
                        <div class=" custome-3-bg b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Tổng bình luận</span>
                                    <h4 class="mb-0 counter">{{ $totalComments }}
                                    </h4>
                                </div>

                                <div class="align-self-center text-center">
                                    <i data-feather="message-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="b-b-success border-5 border-0 card o-hidden">
                        <div class=" custome-4-bg b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Tổng giá trị tổn kho</span>
                                    <h4 class="mb-0 counter">
                                        {{ number_format($totalInventory ?? 0) }}₫
                                    </h4>
                                </div>

                                <div class="align-self-center text-center">
                                    <i data-feather="user-plus"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card o-hidden card-hover">
                        <div class="card-header border-0 pb-1">
                            <div class="card-header-title">
                                <h4>Doanh thu thuần</h4>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="bar-chart-earning"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card o-hidden ">
                        <div class="card-header border-0 pb-1">
                            <div class="card-header-title">
                                <h4>Số đơn hàng & Doanh thu trung bình</h4>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="report-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card o-hidden ">
                        <div class="card-header border-0 pb-1">
                            <div class="card-header-title">
                                <h4>Mua và trả hàng</h4>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="sales-purchase-return-cart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card o-hidden card-hover">
                        <div class="card-header border-0 pb-1">
                            <div class="card-header-title">
                                <h4>Số người sử dụng</h4>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="bar-chart-user"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-12 col-md-6">
                    <div class="h-100">
                        <div class="card o-hidden  ">
                            <div class="card-header border-0 pb-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-header-title">
                                        <h4>Số thông báo</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="pie-chart">
                                    <div id="employ-salary"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-md-6">
                    <div class="h-100">
                        <div class="card o-hidden card-hover">
                            <div class="card-header border-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-header-title">
                                        <h4>Tỷ lệ thanh toán</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="pie-chart">
                                    <div id="pie-chart-visitors"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function toggleDateRange() {
            let filter = document.getElementById("filter").value;
            let dateRange = document.getElementById("date-range");

            if (filter === "custom") {
                dateRange.style.display = "flex";
            } else {
                dateRange.style.display = "none";
            }
        }

        window.onload = function() {
            toggleDateRange();
        };

        const months = @json($months);
        const ordersData = @json($ordersData);
        const aovData = @json($aovData);
        const monthsTotal = @json($monthsTotal);
        const netRevenue = @json($netRevenue);
        const usersData = @json($usersData);
        const monthsUser = @json($monthsUser);
        const monthsNotify = @json($monthsNotify);
        const notifyData = @json($notifyData);
        const monthsDelivery = @json($monthsDelivery);
        const deliveryData = @json($deliveryData);
        const cancelData = @json($cancelData);
        const totalPercen = @json($totalPercen);
    </script>
@endsection
