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
                                <div class="table-responsive table-desi">
                                    <table class="user-table table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Ảnh</th>
                                                <th>Tên</th>
                                                <th>Slug</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        <span>
                                                            @if ($category->image)
                                                                <img src="{{ asset('storage/' . $category->image) }}"
                                                                    alt="{{ $category->name }}"
                                                                    style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                                                            @else
                                                                <span class="text-muted">Không có ảnh</span>
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0)">
                                                            <span class="d-block">{{ $category->name }}</span>
                                                        </a>
                                                    </td>
                                                    <td>{{ $category->slug }}</td>
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <a href="{{ route('admin.category.edit', $category->id) }}">
                                                                    <span class="lnr lnr-pencil"></span>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('admin.category.destroy', $category->id) }}"
                                                                    method="POST" class="delete-form d-inline">
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="pagination-box">
                                {{ $categories->links('pagination::bootstrap-4') }}
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
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
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
