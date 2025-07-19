@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Thêm size mới</h5>
        </div>

        <div class="container-fluid mt-3">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.size.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="form-label">Tên size</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                        </div>
                        <button class="btn btn-primary" type="submit">Thêm size</button>
                        <a href="{{ route('admin.size.index') }}" class="btn btn-outline-dark">Quay lại</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
