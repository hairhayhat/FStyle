@extends('admin.layouts.app')

@section('content')
    <div class="page-body">
        <div class="title-header">
            <h5>Cập nhật thông tin</h5>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Details Start -->
                            <div class="card">
                                <div class="card-body">
                                    <form class="theme-form theme-form-2 mega-form"
                                        action="{{ route('admin.profile.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <div class="row">
                                            <!-- Tên và Số điện thoại trên cùng 1 dòng -->
                                            <div class="mb-4 row align-items-center">
                                                <div class="col-md-6">
                                                    <div class="row align-items-center">
                                                        <label class="form-label-title col-sm-4 mb-0">Tên</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="text" name="name"
                                                                placeholder="Nhập tên"
                                                                value="{{ old('name', Auth::user()->name) }}">
                                                            @error('name')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row align-items-center">
                                                        <label class="form-label-title col-sm-4 mb-0">Số điện thoại</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="text" name="phone"
                                                                placeholder="Nhập số điện thoại"
                                                                value="{{ old('phone', Auth::user()->phone) }}">
                                                            @error('phone')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Email -->
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-2 mb-0">Email</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="email"
                                                        value="{{ Auth::user()->email }}" readonly>
                                                </div>
                                            </div>

                                            <!-- Avatar -->
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-2 col-form-label form-label-title">Ảnh đại diện</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control form-choose" type="file" name="photo">
                                                    @error('photo')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror

                                                    @if(Auth::user()->avatar)
                                                        <div class="mt-2">
                                                            <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar"
                                                                width="100" class="rounded">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button class="btn btn-primary" type="submit">Cập nhật</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Details End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection