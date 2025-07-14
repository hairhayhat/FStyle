@extends('auth.layouts.app')

@section('title', 'FStyle - Đăng ký')

@section('content')
    <div class="login-section">
        <div class="materialContainer">
            <div class="box">
                <div class="login-title">
                    <h2>Đăng ký</h2>
                </div>

                <form method="POST" action="{{ route('register') }}" autocomplete="off" novalidate>
                    @csrf

                    <div class="input">
                        <label for="emailname">Email</label>
                        <input type="email" name="email" id="emailname" autocomplete="off" value="{{ old('email') }}">
                        <span class="spin"></span>
                    </div>
                    <div class="mt-2">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input">
                        <label for="pass">Mật khẩu</label>
                        <input type="password" name="password" id="pass" autocomplete="new-password">
                        <span class="spin"></span>
                    </div>
                    <div class="mt-2">
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="input">
                        <label for="compass">Nhập lại mật khẩu</label>
                        <input type="password" name="password_confirmation" id="compass" autocomplete="new-password">
                        <span class="spin"></span>
                    </div>

                    <div class="button login">
                        <button type="submit">
                            <span>Đăng ký</span>
                            <i class="fa fa-check"></i>
                        </button>
                    </div>
                </form>

                <p class="mt-4">
                    <a href="{{ route('login') }}" class="theme-color">Đã có một tài khoản</a>
                </p>
            </div>
        </div>
    </div>
@endsection
