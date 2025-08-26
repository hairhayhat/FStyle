<div class="col-lg-3">
    <ul class="nav nav-tabs custome-nav-tabs flex-column category-option" id="myTab">
        <li class="nav-item mb-2">
            <a href="{{ route('client.dashboard') }}" class="nav-link font-light" id="5-tab">
                <i class="fas fa-angle-right"></i>Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('client.checkout.index') }}" class="nav-link font-light">
                <i class="fas fa-angle-right"></i>Đơn hàng
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="#" class="nav-link font-light" id="2-tab">
                <i class="fas fa-angle-right"></i>Danh sách yêu thích
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('client.address') }}" class="nav-link font-light" id="3-tab">
                <i class="fas fa-angle-right"></i>Địa chỉ đã lưu
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('client.profile') }}" class="nav-link font-light" id="5-tab">
                <i class="fas fa-angle-right"></i>Thông tin cá nhân
            </a>
        </li>

    </ul>
</div>
