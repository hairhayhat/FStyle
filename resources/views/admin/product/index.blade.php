@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header title-header-1">
            <h5>Danh sách sản phẩm</h5>
            <form class="d-inline-flex">
                <a href="{{ route('admin.product.create') }}" class="align-items-center btn btn-theme">
                    <i data-feather="plus-square"></i> Thêm sản phẩm
                </a>
            </form>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive table-desi">
                                <table class="user-table table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Danh mục</th>
                                            <th>Lượt xem</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr>
                                                {{-- Ảnh --}}
                                                <td>
                                                    <span>
                                                        @if ($product->image && file_exists(storage_path('app/public/' . $product->image)))
                                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                                alt="product" width="60" height="60">
                                                        @else
                                                            <img src="{{ asset('images/default-product.png') }}"
                                                                alt="no image" width="60" height="60">
                                                        @endif
                                                    </span>
                                                </td>

                                                {{-- Tên sản phẩm --}}
                                                <td>
                                                    <a href="{{ route('admin.product.show', $product->id) }}">
                                                        <span class="d-block">{{ $product->name }}</span>
                                                    </a>
                                                </td>

                                                {{-- Danh mục --}}
                                                <td>{{ $product->category->name ?? '---' }}</td>

                                                {{-- Lượt xem --}}
                                                <td>{{ number_format($product->views ?? 0) }}</td>

                                                {{-- Hành động --}}
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="{{ route('admin.product.show', $product->id) }}">
                                                                <span class="lnr lnr-eye"></span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('admin.product.edit', $product->id) }}">
                                                                <span class="lnr lnr-pencil"></span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.product.destroy', $product->id) }}"
                                                                method="POST" class="delete-form"
                                                                data-name="{{ $product->name }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    style="background:none;border:none;padding:0;color:#dc3545;">
                                                                    <span class="lnr lnr-trash"></span>
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
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

                        @if ($products->hasPages())
                            <div class="pagination-box">
                                {{ $products->links('pagination::bootstrap-4') }}
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
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
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
                });
            });
        });
    </script>
@endsection
