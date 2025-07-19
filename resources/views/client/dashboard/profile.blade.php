@extends('client.dashboard.layouts.app')

@section('content')
    <div class="col-lg-9">
        <div class="dashboard-profile dashboard">
            <div class="box-head">
                <h3>Thông tin cá nhân </h3>
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProfile">Sửa</a>
            </div>
            <ul class="dash-profile">
                <li>
                    <div class="left">
                        <h6 class="font-light">Tên</h6>
                    </div>
                    <div class="right">
                        <h6>{{ Auth::user()->name }}</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Ảnh đại diện</h6>
                    </div>
                    <div class="right">
                        @if (Auth::user()->avatar)
                            <img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" alt="Avatar"
                                class="rounded-circle" width="60" height="60">
                        @else
                            <span class="text-muted">Chưa có ảnh</span>
                        @endif
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Email</h6>
                    </div>
                    <div class="right">
                        <h6>{{ Auth::user()->email }}</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Số điện thoại</h6>
                    </div>
                    <div class="right">
                        <h6>{{ Auth::user()->phone }}</h6>
                    </div>
                </li>

                <li>
                    <div class="left">
                        <h6 class="font-light">Địa chỉ</h6>
                    </div>
                    <div class="right">
                        <h6>{{ Auth::user()->getDefaultAddress()->address ?? 'Bạn chưa cập nhật địa chỉ' }}</h6>
                    </div>
                </li>

            </ul>

            <div class="box-head mt-lg-5 mt-3">
                <h3>Thông tin đăng nhập</h3>
            </div>

            <ul class="dash-profile">
                <li class="mb-0">
                    <form method="POST" action="{{ route('client.change.passowrd') }}" novalidate>
                        @csrf
                        <div class="row align-items-center mb-3">
                            <div class="col-md-3 left">
                                <label for="current_password" class="font-light mb-0">Mật khẩu cũ</label>
                            </div>
                            <div class="col-md-6">
                                <input type="password" id="current_password" name="current_password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Nhập mật khẩu cũ" required>
                                @error('current_password')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center mb-3">
                            <div class="col-md-3 left">
                                <label for="new_password" class="font-light mb-0">Mật khẩu mới</label>
                            </div>
                            <div class="col-md-6">
                                <input type="password" id="new_password" name="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror"
                                    placeholder="Nhập mật khẩu mới" required>
                                @error('new_password')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row align-items-center mb-3">
                            <div class="col-md-3 left">
                                <label for="new_password_confirmation" class="font-light mb-0">Xác nhận mật khẩu</label>
                            </div>

                            <div class="col-md-6 ">
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                    class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                    placeholder="Xác nhận mật khẩu mới" required>
                                @error('new_password_confirmation')
                                    <div class="text-danger mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <button type="submit" class="btn btn-solid-default rounded-1">Đổi Mật Khẩu</button>
                            </div>
                        </div>

                    </form>
                </li>
            </ul>
        </div>
    </div>

    <div class="modal fade edit-profile-modal" id="editProfile">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label font-light">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label font-light">Avatar</label>
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar"
                                name="avatar" accept="image/*">
                            @error('avatar')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                            @if (auth()->user()->avatar)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/avatars/' . auth()->user()->avatar) }}" alt="Avatar"
                                        class="rounded-circle" width="60" height="60">
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label font-light">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                required>
                            @error('phone')
                                <div class="text-danger mt-1 small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer pt-0 text-end d-block">
                        <button type="button" class="btn bg-secondary text-white rounded-1"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-solid-default rounded-1">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
