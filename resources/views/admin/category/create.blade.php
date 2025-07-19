@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Thêm danh mục</h5>
        </div>
        <!-- New Product Add Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5>Thông tin</h5>
                                    </div>
                                    <form class="theme-form theme-form-2 mega-form" novalidate
                                        action="{{ route('admin.category.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">

                                            <!-- Tên Danh Mục -->
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-2 mb-0">Tên Danh Mục</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control @error('name') is-invalid @enderror"
                                                        type="text" name="name" value="{{ old('name') }}"
                                                        placeholder="Tên Danh Mục" required>
                                                    @error('name')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Ảnh -->
                                            <!-- Hình ảnh Drag & Drop -->
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-2 mb-0">Hình ảnh</label>
                                                <div class="col-sm-10">
                                                    <div id="drop-area"
                                                        class="border border-2 border-dashed rounded p-4 text-center">
                                                        <p>Kéo thả ảnh vào đây hoặc bấm để chọn</p>
                                                        <input type="file" id="imageInput" name="image"
                                                            accept="image/*"
                                                            class="d-none @error('image') is-invalid @enderror">
                                                        <img id="preview" src="#" alt="Preview"
                                                            class="img-fluid mt-3 d-none" style="max-height: 200px;">
                                                    </div>
                                                    @error('image')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Submit -->
                                            <div class="mb-4 row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button class="btn btn-primary" type="submit">Thêm danh mục</button>
                                                    <a href="" class="btn btn-secondary">Quay
                                                        lại</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
