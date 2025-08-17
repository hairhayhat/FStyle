@extends('admin.layouts.app')

@section('content')
    <!-- index body start -->
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="title-header d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Thống kê</h5>

                    <!-- Select filter theo thời gian -->
                    <form method="GET" action="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2">
                        <select name="filter" id="filter"
                            class="form-select form-select-sm shadow-sm border-0 rounded-pill px-3"
                            onchange="toggleDateRange()" style="min-width: 160px;">
                            <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                            <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>Tuần này</option>
                            <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>Tháng này</option>
                            <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>Năm nay</option>
                            <option value="custom" {{ request('filter') == 'custom' ? 'selected' : '' }}>Tùy chọn
                            </option>
                        </select>

                        <!-- Date range (ẩn/hiện theo select) -->
                        <div id="date-range" class="d-flex align-items-center gap-2" style="display: none;">
                            <input type="date" name="from_date"
                                class="form-control form-control-sm shadow-sm rounded-pill px-3"
                                value="{{ request('from_date') }}">
                            <span class="fw-bold">-</span>
                            <input type="date" name="to_date"
                                class="form-control form-control-sm shadow-sm rounded-pill px-3"
                                value="{{ request('to_date') }}">
                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Lọc</button>
                        </div>
                    </form>
                </div>
                <!-- chart caard section start -->
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
                                    <span class="m-0">Reviews</span>
                                    <h4 class="mb-0 counter">893
                                        <span class="badge badge-light-secondary grow ">
                                            <i data-feather="trending-up"></i>8.5%</span>
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
                <!-- chart caard section End -->

                <!-- Earning chart star-->
                <div class="col-xl-4">
                    <div class="card o-hidden card-hover">
                        <div class="card-header border-0 pb-1">
                            <div class="card-header-title">
                                <h4>Doanh thu thuần theo tháng</h4>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="bar-chart-earning"></div>
                        </div>
                    </div>
                </div>
                <!-- Earning chart end-->

                <!-- Earning chart star-->
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
                <!-- Earning chart  end-->

                <!-- Transactions start-->
                <div class="col-xxl-4 col-md-6">
                    <div class="card o-hidden card-hover">
                        <div class="card-header border-0">
                            <div class="card-header-title">
                                <h4>Transactions</h4>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <div>
                                <div class="table-responsive table-desi">
                                    <table class="user-table transactions-table table border-0">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="transactions-icon">
                                                        <i data-feather="shield"></i>
                                                    </div>
                                                    <div class="transactions-name">
                                                        <h6>Wallets</h6>
                                                        <p>Starbucks</p>
                                                    </div>
                                                </td>

                                                <td class="lost">-$74</td>
                                            </tr>
                                            <tr>
                                                <td class="td-color-1">
                                                    <div class="transactions-icon">
                                                        <i data-feather="check"></i>
                                                    </div>
                                                    <div class="transactions-name">
                                                        <h6>Bank Transfer</h6>
                                                        <p>Add Money</p>
                                                    </div>
                                                </td>

                                                <td class="success">+$125</td>
                                            </tr>
                                            <tr>
                                                <td class="td-color-2">
                                                    <div class="transactions-icon">
                                                        <i data-feather="dollar-sign"></i>
                                                    </div>
                                                    <div class="transactions-name">
                                                        <h6>Paypal</h6>
                                                        <p>Add Money</p>
                                                    </div>
                                                </td>

                                                <td class="lost">-$50</td>
                                            </tr>
                                            <tr>
                                                <td class="td-color-3">
                                                    <div class="transactions-icon">
                                                        <i data-feather="credit-card"></i>
                                                    </div>
                                                    <div class="transactions-name">
                                                        <h6>Mastercard</h6>
                                                        <p>Ordered Food</p>
                                                    </div>
                                                </td>

                                                <td class="lost">-$40</td>
                                            </tr>
                                            <tr>
                                                <td class="td-color-4 pb-0">
                                                    <div class="transactions-icon">
                                                        <i data-feather="trending-up"></i>
                                                    </div>
                                                    <div class="transactions-name">
                                                        <h6>Transfer</h6>
                                                        <p>Refund</p>
                                                    </div>
                                                </td>

                                                <td class="success pb-0">+$90</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Transactions end-->

                <!-- visitors chart start-->
                <div class="col-xxl-4 col-md-6">
                    <div class="h-100">
                        <div class="card o-hidden card-hover">
                            <div class="card-header border-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-header-title">
                                        <h4>Visitors</h4>
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
                <!-- visitors chart end-->

                <!-- New & Update start-->
                <div class="col-xxl-4 col-md-6">
                    <div class="card o-hidden card-hover">
                        <div class="card-header border-0">
                            <div class="card-header-title">
                                <h4>New & Update</h4>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <ul class="StepProgress">
                                <li class="StepProgress-item">
                                    <strong>Update Product</strong>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                </li>
                                <li class="StepProgress-item">
                                    <strong>James liked Nike Shoes</strong>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                </li>
                                <li class="StepProgress-item">
                                    <strong>john just buy your product</strong>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                </li>
                                <li class="StepProgress-item">
                                    <strong>Jihan dor just save your product</strong>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- New & Update end-->
                <!-- Browser States start-->
                <div class="col-xxl-4 col-md-6">
                    <div class="card o-hidden card-hover">
                        <div class="card-header border-0">
                            <div class="card-header-title">
                                <h4>Top 5 sản phẩm được xem nhiều nhất</h4>
                            </div>
                        </div>

                        <div class="card-body pt-0">
                            <ul class="brower-states">
                                @foreach ($topTierProducts as $topTier)
                                    <li class="brower-item">
                                        <a href="{{ route('admin.product.show', ['product' => $topTier->id]) }}">
                                            <div class="browser-image">
                                                <img src="{{ asset('storage/' . $topTier->image) }}" class="img-fluid"
                                                    alt="">
                                                <h5>{{ \Illuminate\Support\Str::limit($topTier->name, 15) }}</h5>
                                            </div>
                                        </a>
                                        <div class="browser-progressbar">
                                            <h6>{{ $topTier->views }} Lượt xem</h6>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Browser States end-->
            </div>
        </div>
        <!-- Container-fluid Ends-->
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

        // chạy khi load lại trang (giữ trạng thái cũ)
        window.onload = function() {
            toggleDateRange();
        };

        //bảng so sánh Số đơn hàng & Doanh thu trung bình
        const months = @json($months);
        const ordersData = @json($ordersData);
        const aovData = @json($aovData);
        const monthsTotal = @json($monthsTotal);
        const netRevenue = @json($netRevenue);
    </script>
@endsection
