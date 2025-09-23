@extends('admin.layouts.app')

@section('content')
    <!-- Page Body Start -->
    <div class="page-body-wrapper">
        <!-- Page Sidebar Ends-->

        <!-- Container-fluid starts-->
        <div class="page-body">
            <div class="title-header title-header-1">
                <h5>All Users</h5>

            </div>
            <!-- All User Table Start -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <form action="" method="GET" id="userFilterForm"
                                        class="d-flex align-items-center gap-2 flex-wrap">
                                        <input type="hidden" name="status" value="{{ request('status', 'pending') }}">

                                        <select name="sort" class="form-select form-select-sm w-auto">
                                            <option value="desc"
                                                {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất
                                            </option>
                                        </select>

                                        <select name="per_page" class="form-select form-select-sm w-auto">
                                            <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 /
                                                trang</option>
                                            <option value="10"
                                                {{ request('per_page', default: 10) == 10 ? 'selected' : '' }}>10 / trang
                                            </option>
                                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 /
                                                trang</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 /
                                                trang</option>
                                        </select>

                                        <div class="d-flex align-items-center gap-2">
                                            <label>
                                                <input type="radio" name="email_verified" value=""
                                                    {{ request('email_verified') == '' ? 'checked' : '' }}>
                                                Tất cả
                                            </label>
                                            <label>
                                                <input type="radio" name="email_verified" value="unverified"
                                                    {{ request('email_verified') == 'unverified' ? 'checked' : '' }}>
                                                Chưa xác minh
                                            </label>
                                            <label>
                                                <input type="radio" name="email_verified" value="verified"
                                                    {{ request('email_verified') == 'verified' ? 'checked' : '' }}>
                                                Đã xác minh
                                            </label>
                                        </div>
                                    </form>
                                </div>

                                <div id="userTableWrapper" data-url="{{ route('admin.users.index') }}">
                                    @include('admin.partials.table-users', ['users' => $users])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- All User Table Ends-->

        </div>
        <!-- Container-fluid end -->
    </div>
@endsection
