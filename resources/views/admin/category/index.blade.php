@extends('admin.layouts.app')

@section('content')
    <!-- Page Body Start -->
    <div class="page-body-wrapper">
        <div class="page-body">

            <div class="title-header title-header-1">
                <h5>Danh mục</h5>
            </div>

            <!-- Category List Start -->
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
                                            <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 /
                                                trang</option>
                                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 /
                                                trang</option>
                                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 /
                                                trang</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 /
                                                trang</option>
                                        </select>
                                    </form>
                                </div>
                                <div id="orderTableWrapper" data-url="{{ route('admin.category.index') }}">
                                    @include('admin.partials.table-categories', ['categories' => $categories])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Category List End -->
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Bạn có chắc muốn xoá?',
                        text: "Hành động này không thể hoàn tác!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: "{{ session('error') }}",
                    timer: 2500,
                    showConfirmButton: false
                });
            @endif
            });
    </script>
@endsection