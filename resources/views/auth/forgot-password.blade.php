@extends('auth.layouts.app')

@section('title', 'FStyle - Quên mật khẩu')

@section('content')
    <div class="login-section">
        <div class="materialContainer">
            <div class="box">
                <div class="login-title">
                    <h2>Quên mật khẩu</h2>
                </div>

                <form method="POST" action="{{ route('password.email') }}" novalidate>
                    @csrf
                    <div class="input">
                        <label for="email">Nhập email</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <div class="text-danger mt-3">{{ $message }}</div>
                    @enderror

                    <div class="button login button-1">
                        <button type="submit">
                            <span>Gửi liên kết</span>
                            <i class="fa fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
