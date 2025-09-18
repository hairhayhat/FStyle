@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header title-header-1">
            <h5>Danh sách sản phẩm</h5>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                <form action="" method="GET" id="productFilterForm"
                                    class="d-flex align-items-center gap-2 flex-wrap">
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                    <select name="sort" class="form-select form-select-sm w-auto">
                                        <option value="desc" {{ request('sort', 'desc') == 'desc' ? 'selected' : '' }}>Mới
                                            nhất</option>
                                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất
                                        </option>
                                    </select>

                                    <select name="per_page" class="form-select form-select-sm w-auto">
                                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5 /
                                            trang</option>
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 /
                                            trang</option>
                                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 / trang
                                        </option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / trang
                                        </option>
                                    </select>

                                    <select name="category_id" class="form-select form-select-sm w-auto">
                                        <option value="">Tất cả danh mục</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>                        
                            </div>
                            <div id="productTableWrapper" data-url="{{ route('admin.product.index') }}">
                                @include('admin.partials.table-products', ['products' => $products])
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
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('.delete-form');
                if (form) {
                    e.preventDefault();
                    const productName = form.dataset.name || 'sản phẩm';
                    Swal.fire({
                        title: 'Xác nhận xoá?',
                        text: `Bạn có chắc muốn xoá "${productName}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
