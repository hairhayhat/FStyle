@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header d-flex justify-content-between align-items-center">
            <h5>Danh sách màu sắc</h5>
            <a href="{{ route('admin.color.create') }}" class="btn btn-primary">+ Thêm màu</a>
        </div>

        <div class="container-fluid mt-3">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tên màu</th>
                                <th>Mã màu</th>
                                <th>Hiển thị</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($colors as $color)
                                <tr>
                                    <td>{{ $color->name }}</td>
                                    <td>{{ $color->code }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div
                                                style="width: 30px; height: 30px; background-color: {{ $color->code }}; border: 1px solid #ccc; border-radius: 4px;">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.color.edit', $color->id) }}"
                                            class="btn btn-sm btn-warning">Sửa</a>
                                        <form action="{{ route('admin.color.destroy', $color->id) }}" method="POST"
                                            style="display:inline-block;" onsubmit="return confirm('Xóa màu này?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Xoá</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Chưa có màu nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
