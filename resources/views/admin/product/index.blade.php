@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Danh sách sản phẩm</h5>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="table-responsive table-desi table-product">
                                <table class="table table-1d all-package align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Danh mục</th>
                                            <th>Lượt xem</th>
                                            <th class="text-center">Tuỳ chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr>
                                                {{-- Ảnh --}}
                                                <td>
                                                    @if ($product->image && file_exists(storage_path('app/public/' . $product->image)))
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            class="img-thumbnail" alt="Ảnh sản phẩm" width="60"
                                                            height="60">
                                                    @else
                                                        <span class="text-muted small fst-italic">Chưa có</span>
                                                    @endif
                                                </td>

                                                {{-- Tên sản phẩm --}}
                                                <td>{{ $product->name }}</td>

                                                {{-- Danh mục --}}
                                                <td>{{ $product->category->name ?? '---' }}</td>

                                                {{-- Lượt xem --}}
                                                <td>{{ number_format($product->views ?? 0) }}</td>

                                                {{-- Tuỳ chọn --}}
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-3">
                                                        <a href="{{ route('admin.product.show', $product->id) }}"
                                                            class="text-info" title="Xem chi tiết" data-bs-toggle="tooltip">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.product.edit', $product->id) }}"
                                                            class="text-primary" title="Chỉnh sửa" data-bs-toggle="tooltip">
                                                            <i class="lnr lnr-pencil"></i>
                                                        </a>
                                                        <form action="{{ route('admin.product.destroy', $product->id) }}"
                                                            method="POST" class="delete-form m-0"
                                                            data-name="{{ $product->name }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link p-0 text-danger"
                                                                title="Xoá sản phẩm" data-bs-toggle="tooltip">
                                                                <i class="far fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    Chưa có sản phẩm nào.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Pagination --}}
                        @if ($products->hasPages())
                            <div class="card-footer">
                                <div class="pagination-box d-flex justify-content-center">
                                    {{ $products->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tooltip Bootstrap
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

            // SweetAlert chỉ cho nút xóa
            document.querySelectorAll('.delete-form').forEach(form => {
                const deleteBtn = form.querySelector('button[type="submit"]');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function(e) {
                        e.preventDefault(); // Ngăn submit ngay
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
                    });
                }
            });
        });
    </script>
@endsection
