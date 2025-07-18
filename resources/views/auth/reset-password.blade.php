@extends('auth.layouts.app')

@section('title', 'FStyle - Đặt lại mật khẩu')

@section('content')
    <div class="login-section">
        <div class="materialContainer">
            <div class="box">
                <div class="login-title">
                    <h2>Đặt mật khẩu</h2>
                </div>

                <input type="text" name="fake_username" style="position: absolute; top: -1000px; left: -1000px;"
                    autocomplete="off">
                <input type="password" name="fake_password" style="position: absolute; top: -1000px; left: -1000px;"
                    autocomplete="new-password">
                <input type="password" name="fake_password_confirmation"
                    style="position: absolute; top: -1000px; left: -1000px;" autocomplete="new-password">

                <form method="POST" action="{{ route('password.store') }}" novalidate autocomplete="off">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="input">
                        <label for="email">Nhập email</label>
                        <input type="email" name="email" id="email" autocomplete="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    </div>
                    <div class="mt-3">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input">
                        <label for="password">Mật khẩu mới</label>
                        <input type="password" name="password" id="password" autocomplete="new-password"
                            class="form-control @error('password') is-invalid @enderror" required>
                    </div>
                    <div class="mt-3">
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input">
                        <label for="password_confirmation">Xác minh mật khẩu</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            autocomplete="new-password"
                            class="form-control @error('password_confirmation') is-invalid @enderror" required>
                    </div>
                    <div class="mt-3">
                        @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="button login button-1">
                        <button type="submit">
                            <span>Xác nhận</span>
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
