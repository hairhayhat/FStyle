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
                                    <form class="theme-form theme-form-2 mega-form">
                                        <div class="row">
                                            <!-- Tên và Số điện thoại trên cùng 1 dòng -->
                                            <div class="mb-4 row align-items-center">
                                                <div class="col-md-6">
                                                    <div class="row align-items-center">
                                                        <label class="form-label-title col-sm-4 mb-0">Tên</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="text"
                                                                placeholder="Nhập tên"
                                                                value="{{ Auth::user()->name ?? old('name', '') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row align-items-center">
                                                        <label class="form-label-title col-sm-4 mb-0">Số điện thoại</label>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" type="text"
                                                                placeholder="Nhập số điện thoại"
                                                                value="{{ Auth::user()->phone ?? old('phone', '') }}">
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

                                            <!-- Photo -->
                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-2 col-form-label form-label-title">Photo</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control form-choose" type="file"
                                                        id="formFileMultiple" multiple>
                                                </div>
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
    <!-- Settings Section End -->
@endsection
