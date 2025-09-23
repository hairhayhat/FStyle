@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Thống kê người dùng</h5>
            <form id="filterFormForUser" class="d-flex align-items-center gap-2">
                <input type="date" class="form-control form-control-sm" id="fromDateForUser">
                <span>đến</span>
                <input type="date" class="form-control form-control-sm" id="toDateForUser">
                <button type="submit" class="btn btn-sm btn-primary">Lọc</button>
            </form>
        </div>
        <div class="container-fluid">
            <div class="row">

                <div class="col-xxl-12 col-md-6">
                    <div class="h-100">
                        <div class="card o-hidden card-hover">
                            <div class="card-header border-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-header-title">
                                        <h4>Top 5 khách hàng trung thành</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="pie-chart">
                                    <div id="loyal-customers-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-12 col-md-6">
                    <div class="h-100">
                        <div class="card o-hidden card-hover">
                            <div class="card-header border-0">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="card-header-title">
                                        <h4>Top 5 khách hàng giá trị nhất</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="pie-chart">
                                    <div id="valuable-customers-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
