@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" style="height: 50px;">
        @endcomponent
    @endslot

    # 🎉 Chào mừng đến với {{ config('app.name') }}!

    Xin chào **{{ $user->name }}**,

    Cảm ơn bạn đã đăng ký tài khoản tại {{ config('app.name') }} - Nơi cung cấp các sản phẩm thời trang nam đẳng cấp!

    @component('mail::panel', ['color' => '#f8f9fa'])
        Để hoàn tất đăng ký, vui lòng xác thực email của bạn bằng cách nhấn nút bên dưới:
    @endcomponent

    @component('mail::button', ['url' => $url, 'color' => 'primary'])
        🔐 XÁC THỰC NGAY
    @endcomponent

    @component('mail::panel', ['color' => '#fff3cd'])
        ⏳ Liên kết xác thực sẽ hết hạn sau 24 giờ
    @endcomponent

    Nếu bạn không thực hiện đăng ký này, vui lòng bỏ qua email hoặc <a href="{{ config('app.url') }}/contact">liên hệ hỗ
        trợ</a>.

    Trân trọng,<br>
    **Đội ngũ {{ config('app.name') }}**

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            <small style="color: #6c757d;">
                Địa chỉ: 123 Đường Thời Trang, Quận 1, TP.HCM<br>
                Hotline: 1900 1234 | Email: support@thoitrangnam.vn
            </small>
            <div style="margin-top: 10px;">
                <a href="{{ config('app.url') }}" style="margin-right: 10px;">Trang chủ</a>
                <a href="{{ config('app.url') }}/collections" style="margin-right: 10px;">Bộ sưu tập</a>
                <a href="{{ config('app.url') }}/blog" style="margin-right: 10px;">Tạp chí phong cách</a>
            </div>
        @endcomponent
    @endslot
@endcomponent
