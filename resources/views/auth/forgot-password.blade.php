@extends('auth.layouts.app')

@section('title', 'FStyle - Quên mật khẩu')

@section('content')
    <div class="login-section">
        <div class="materialContainer">
            <div class="box">
                <div class="login-title">
                    <h2>Quên mật khẩu</h2>
                </div>
                <div class="input">
                    <label for="emailname">Nhập email</label>
                    <input type="text" name="name" class="is-invalid" id="emailname">
                    <span class="spin"></span>
                </div>
                <div class="button login button-1">
                    <button onclick="location.href = 'index.html';">
                        <span>Send</span>
                        <i class="fa fa-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
