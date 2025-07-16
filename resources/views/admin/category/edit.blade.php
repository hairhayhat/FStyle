@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Sửa danh mục</h5>
        </div>

        <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data"
            class="theme-form theme-form-2 mega-form">
            @csrf
            @method('PUT')

            <div class="mb-4 row align-items-center">
                <label class="form-label-title col-sm-2 mb-0">Tên Danh Mục</label>
                <div class="col-sm-10">
                    <input class="form-control" name="name" type="text" value="{{ old('name', $category->name) }}">
                </div>
            </div>
            <div class="mb-4 row align-items-center">
                <label class="form-label-title col-sm-2 mb-0">Hình ảnh</label>
                <div class="col-sm-10">
                    @if ($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" width="100" class="mb-2">
                    @endif
                    <input class="form-control" type="file" name="image">
                </div>
            </div>

            <div class="row">
                <div class="offset-sm-2 col-sm-10">
                    <button class="btn btn-primary" type="submit">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
@endsection
