@extends('auth.layouts.app')

@section('title', 'FStyle - Đăng nhập')

@section('content')
    <div class="login-section">
        <div class="materialContainer">
            <div class="box">
                <div class="login-title">
                    <h2>Đăng nhập</h2>
                </div>

                <input type="text" name="fake_username" style="position: absolute; top: -1000px; left: -1000px;"
                    autocomplete="off">
                <input type="password" name="fake_password" style="position: absolute; top: -1000px; left: -1000px;"
                    autocomplete="new-password">

                <form method="POST" action="{{ route('login') }}" autocomplete="off" novalidate>
                    @csrf

                    <div class="input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" autocomplete="off" required>
                        <span class="spin"></span>
                    </div>

                    <div class="input">
                        <label for="password">Mật khẩu</label>
                        <input type="password" name="password" id="password" autocomplete="new-password" required>
                        <span class="spin"></span>
                    </div>

                    {{-- <div class="mt-3">
                        <label class="remember-checkbox">
                            <input type="checkbox" name="remember" id="remember">
                            <span class="checkmark"></span>
                            Ghi nhớ đăng nhập
                        </label>
                    </div> --}}

                    <a href="{{ route('password.request') }}" class="pass-forgot">Quên mật khẩu?</a>

                    <div class="button login">
                        <button type="submit">
                            <span>Đăng nhập</span>
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </form>

                <p class="sign-category mb-3">
                    <span>Hoặc đăng nhập với</span>
                </p>

                <div class="mb-3">
                    <a href="{{ route('login.google') }}">
                        <div class="social-media google-media">
                            <img src="{{ asset('admin/assets/images/google.png') }}" class="img-fluid" alt="">
                            <h6>Google</h6>
                        </div>
                    </a>
                </div>

                <p>Chưa tạo tài khoản <a href="{{ route('register') }}" class="theme-color">Đăng ký ngay</a></p>
            </div>
        </div>
    </div>
@endsection
