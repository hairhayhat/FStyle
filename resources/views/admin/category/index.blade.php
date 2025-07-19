@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Danh mục</h5>
        </div>
        <!-- Category List Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Slug</th>
                                            <th>Ảnh</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            <tr>
                                                <td>{{ $category->id }}</td>
                                                <td>{{ $category->name }}</td>
                                                <td>{{ $category->slug }}</td>
                                                <td>
                                                    @if ($category->image)
                                                        <img src="{{ asset('storage/' . $category->image) }}"
                                                            alt="{{ $category->name }}" width="50">
                                                    @else
                                                        Không có ảnh
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.category.edit', $category->id) }}"
                                                        class="btn btn-sm btn-primary">Sửa</a>

                                                    <form action="{{ route('admin.category.destroy', $category->id) }}"
                                                        method="POST" class="d-inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger delete-btn">Xoá</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
            const deleteForms = document.querySelectorAll('.delete-form');

            deleteForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // chặn gửi form ngay

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
                            form.submit(); // chỉ gửi nếu confirm
                        }
                    });
                });
            });
        });
    </script>
@endsection
