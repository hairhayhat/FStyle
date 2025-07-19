@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Cập nhật màu</h5>
        </div>

        <div class="container-fluid mt-3">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.color.update', $color->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="form-label">Tên màu</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $color->name) }}">
                        </div>
                        <div class="mb-4">
                            <label for="code" class="form-label">Mã màu</label>
                            <input type="text" name="code" class="form-control"
                                value="{{ old('code', $color->code) }}">
                        </div>
                        <button class="btn btn-primary" type="submit">Cập nhật</button>
                        <a href="{{ route('admin.color.index') }}" class="btn btn-outline-dark">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
