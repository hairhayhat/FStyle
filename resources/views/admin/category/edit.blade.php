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
            <div class="mb-4 row align-items-start">
                <label class="form-label-title col-sm-2 mb-0">Hình ảnh</label>
                <div class="col-sm-10">
                    <div class="row">
                        {{-- Ảnh hiện tại --}}
                        <div class="col-md-6">
                            <label class="form-label">Ảnh hiện tại:</label>
                            <div class="border p-2 rounded text-center bg-light">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" id="currentImage"
                                        class="img-fluid" style="max-height: 150px;">
                                @else
                                    <p class="text-muted">Chưa có ảnh</p>
                                @endif
                            </div>
                        </div>

                        {{-- Ảnh mới --}}
                        <div class="col-md-6">
                            <label class="form-label">Ảnh mới:</label>
                            <div id="drop-area" class="border border-2 border-dashed rounded p-4 text-center">
                                <p class="text-muted">Kéo thả ảnh vào đây hoặc click để chọn</p>

                                {{-- Input ẩn --}}
                                <input class="d-none" type="file" name="image" id="imageInput" accept="image/*">

                                {{-- Ảnh mới preview --}}
                                <img id="preview" class="img-fluid d-none mt-2" style="max-height: 150px;">
                            </div>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
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
