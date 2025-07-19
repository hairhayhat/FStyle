<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Xác thực Email - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f9fc;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #e22454;
            padding: 20px;
            text-align: center;
        }

        .header img {
            height: 50px;
        }

        .content {
            padding: 30px 20px;
        }

        .content h1 {
            font-size: 20px;
            color: #e22454;
        }

        .content p {
            margin: 15px 0;
            font-size: 15px;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 30px;
            background-color: #e22454;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
        }

        .panel {
            background: #fff3cd;
            color: #856404;
            padding: 10px 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .footer {
            background-color: #f1f3f5;
            text-align: center;
            font-size: 12px;
            color: #666;
            padding: 20px;
        }

        .footer a {
            color: #e22454;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="header">
            <a href="{{ config('app.url') }}">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}">
            </a>
        </div>
        <div class="content">
            <h1>🎉 Chào mừng đến với {{ config('app.name') }}!</h1>

            <p>Xin chào <strong>{{ $user->name }}</strong>,</p>

            <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>{{ config('app.name') }}</strong> – Nơi cung cấp các sản phẩm
                thời trang nam đẳng cấp!</p>

            <p>Để hoàn tất đăng ký, vui lòng xác thực email của bạn bằng cách nhấn nút bên dưới:</p>

            <p style="text-align: center;">
                <a href="{{ $url }}" class="button">🔐 XÁC THỰC NGAY</a>
            </p>

            <div class="panel">
                ⏳ Liên kết xác thực sẽ hết hạn sau {{ $expire ?? 60 }} phút.
            </div>

            <p>Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email hoặc <a
                    href="{{ config('app.url') }}/contact">liên hệ hỗ trợ</a>.</p>

            <p>Trân trọng,<br>
                <strong>Đội ngũ {{ config('app.name') }}</strong>
            </p>
        </div>
        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            Địa chỉ: 123 Đường Thời Trang, Quận 1, TP.HCM – Hotline: 1900 1234<br>
            <a href="{{ config('app.url') }}">Trang chủ</a> |
            <a href="{{ config('app.url') }}/collections">Bộ sưu tập</a> |
            <a href="{{ config('app.url') }}/blog">Tạp chí phong cách</a>
        </div>
    </div>
</body>

</html>
