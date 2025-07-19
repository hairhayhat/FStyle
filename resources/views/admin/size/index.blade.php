@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header d-flex justify-content-between align-items-center">
            <h5>Danh sách size</h5>
            <a href="{{ route('admin.size.create') }}" class="btn btn-primary">+ Thêm size</a>
        </div>

        <div class="container-fluid mt-3">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tên size</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sizes as $size)
                                <tr>
                                    <td>{{ $size->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.size.edit', $size->id) }}"
                                            class="btn btn-sm btn-warning">Sửa</a>
                                        <form action="{{ route('admin.size.destroy', $size->id) }}" method="POST"
                                            style="display:inline-block;" onsubmit="return confirm('Xoá size này?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Xoá</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Chưa có size</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
