@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Thống kê sản phẩm</h5>
            <form id="filterFormForProduct" class="d-flex align-items-center gap-2">
                <input type="date" class="form-control form-control-sm" id="fromDateForProduct">
                <span>đến</span>
                <input type="date" class="form-control form-control-sm" id="toDateForProduct">
                <button type="submit" class="btn btn-sm btn-primary">Lọc</button>
            </form>
        </div>
        <div class="container-fluid">
            <div class="row">

                <div class="col-xl-12 col-lg-12 col-md-6">
                    <div class="card o-hidden ">
                        <div class="card-header border-0 pb-1 d-flex justify-content-between align-items-center">
                            <div class="card-header-title">
                                <h4 class="mb-0">Lợi nhuận theo sản phẩm</h4>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="profit-by-product-chart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-4 col-md-6">
                    <div class="h-100">
                        <div class="card o-hidden card-hover">
                            <div class="card-header border-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-header-title">
                                        <h4>Tỷ lệ bán hàng theo sản phẩm</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="pie-chart">
                                    <div id="sales-performance-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
