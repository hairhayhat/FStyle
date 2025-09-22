@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="b-b-primary border-5 border-0 card o-hidden">
                        <div class="custome-1-bg b-r-4 card-body">
                            <div class="media align-items-center static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Tổng số sản phẩm</span>
                                    <h4 class="mb-0 counter">{{ $productCount }}
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i data-feather="box"></i>
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
                                    <span class="m-0">Tổng số mã giảm giá</span>
                                    <h4 class="mb-0 counter">{{ $voucherCount }}
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
                                    <span class="m-0">Tổng số đánh giá</span>
                                    <h4 class="mb-0 counter">{{ $commeontCount }}
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
                                    <span class="m-0">Tổng số người dùng</span>
                                    <h4 class="mb-0 counter">{{ $userCount }}
                                    </h4>
                                </div>

                                <div class="align-self-center text-center">
                                    <i data-feather="user-plus"></i>
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
                                        <h4>Tổng số đơn hàng</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="pie-chart">
                                    <div id="pie-chart-orders"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card o-hidden">
                        <div class="card-header border-0 pb-1 d-flex justify-content-between align-items-center">
                            <div class="card-header-title">
                                <h4 class="mb-0">Lợi nhuận</h4>
                            </div>
                            <form id="filterForm" class="d-flex align-items-center gap-2">
                                <input type="date" class="form-control form-control-sm" id="fromDate">
                                <span>đến</span>
                                <input type="date" class="form-control form-control-sm" id="toDate">
                                <button type="submit" class="btn btn-sm btn-primary">Lọc</button>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div id="report-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-6">
                    <div class="card o-hidden ">
                        <div class="card-header border-0 pb-1 d-flex justify-content-between align-items-center">
                            <div class="card-header-title">
                                <h4 class="mb-0">Doanh thu gộp & Doanh thu thuần</h4>
                            </div>

                            <form id="filterRevenueForm" class="d-flex align-items-center gap-2">
                                <input type="date" class="form-control form-control-sm" id="fromDateSummary">
                                <span>đến</span>
                                <input type="date" class="form-control form-control-sm" id="toDateSummary">
                                <button type="submit" class="btn btn-sm btn-primary">Lọc</button>
                            </form>
                        </div>

                        <div class="card-body p-0">
                            <div id="saler-summary"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
