@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Thống kê đánh giá</h5>
            <form id="filterFormForComment" class="d-flex align-items-center gap-2">
                <input type="date" class="form-control form-control-sm" id="fromDateForComment">
                <span>đến</span>
                <input type="date" class="form-control form-control-sm" id="toDateForComment">
                <button type="submit" class="btn btn-sm btn-primary">Lọc</button>
            </form>
        </div>
        <div class="container-fluid">
            <div class="row">

                <div class="col-xl-12 col-lg-12 col-md-6">
                    <div class="card o-hidden ">
                        <div class="card-header border-0 pb-1 d-flex justify-content-between align-items-center">
                            <div class="card-header-title">
                                <h4 class="mb-0">Tỷ lệ đánh giá</h4>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="comment-by-rating-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-md-6">
                    <div class="h-100">
                        <div class="card o-hidden card-hover">
                            <div class="card-header border-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-header-title">
                                        <h4>Tỷ lệ người dùng đánh giá sau mua</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="pie-chart">
                                    <div id="rating-rate-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-8 col-md-6">
                    <div class="h-100">
                        <div class="card o-hidden card-hover">
                            <div class="card-header border-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-header-title">
                                        <h4>Top 5 sản phẩm có lượt đánh giá cao nhất</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="pie-chart">
                                    <div id="top-tier-rating-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
