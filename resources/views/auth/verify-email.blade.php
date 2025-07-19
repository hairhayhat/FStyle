@extends('auth.layouts.app')

@section('title', 'FStyle - Xác minh Email')

@section('content')
    <div class="login-section">
        <div class="materialContainer">
            <div class="box">
                <div class="login-title">
                    <h2>Xác minh Email</h2>
                </div>

                <p style="text-align: center; margin-bottom: 1rem;">
                    Vui lòng kiểm tra email để xác minh tài khoản của bạn.<br>
                    Nếu bạn không nhận được email, bạn có thể yêu cầu gửi lại liên kết bên dưới.
                </p>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <div class="button login button-1">
                        <button type="submit">
                            <span>Gửi lại email xác minh</span>
                            <i class="fa fa-envelope"></i>
                        </button>
                    </div>
                </form>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-submit">
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
