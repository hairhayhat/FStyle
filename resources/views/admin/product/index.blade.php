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
                                <table class="table table-1d all-package">
                                    <thead>
                                        <tr>
                                            <th>Ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Danh mục</th>
                                            <th>Lượt xem</th>
                                            <th>Tuỳ chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                {{-- Ảnh --}}
                                                <td>
                                                    @if ($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            class="img-fluid" alt="" width="60">
                                                    @else
                                                        <span class="text-muted">Chưa có</span>
                                                    @endif
                                                </td>

                                                {{-- Tên sản phẩm --}}
                                                <td>
                                                    <a href="javascript:void(0)">{{ $product->name }}</a>
                                                </td>

                                                {{-- Danh mục --}}
                                                <td>
                                                    <a href="javascript:void(0)">
                                                        {{ $product->category->name ?? '---' }}
                                                    </a>
                                                </td>

                                                {{-- Lượt xem --}}
                                                <td>{{ $product->views ?? 0 }}</td>
                                                {{-- Tuỳ chọn --}}
                                                <td>
                                                    <ul class="d-flex gap-2">
                                                        <li>
                                                            <a href="{{ route('admin.product.show', $product->id) }}"
                                                                class="text-info" title="Xem chi tiết">
                                                                <i class="far fa-eye"></i>
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
                                                                <button type="submit" class="btn p-0 text-danger"
                                                                    style="border: none; background: transparent;">
                                                                    <i class="far fa-trash-alt theme-color"></i>
                                                                </button>
                                                            </form>

                                                        </li>
                                                    </ul>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        {{-- Pagination --}}
                        <div class="pagination-box">
                            <nav class="ms-auto me-auto" aria-label="...">
                                {{ $products->links('pagination::bootstrap-4') }}
                            </nav>
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
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Ngăn form submit ngay

                    const productName = this.getAttribute('data-name') || 'sản phẩm';

                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: `Bạn muốn xoá "${productName}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Xoá',
                        cancelButtonText: 'Huỷ'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit(); // Nếu người dùng đồng ý -> submit
                        }
                    });
                });
            });
        });
    </script>
@endsection
