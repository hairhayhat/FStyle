@extends('client.dashboard.layouts.app')

@section('content')
    <div class="col-lg-9">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="dash">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title title title1 title-effect">
                            <h2> Dashboard </h2>
                        </div>
                        <div class="welcome-msg">
                            <h6 class="font-light">
                                Xin chào, <span>{{ Auth::user()->name }}</span>!
                            </h6>
                            <p class="font-light">
                                Từ bảng điều khiển tài khoản của bạn, bạn có thể xem nhanh hoạt động gần đây
                                và cập nhật thông tin cá nhân. Chọn mục bên dưới để xem hoặc chỉnh sửa.
                            </p>
                        </div>

                        <div class="order-box-contain my-4">
                            <div class="row g-4">
                                <div class="col-lg-4 col-sm-6">
                                    <div class="order-box">
                                        <div class="order-box-image">
                                            <img src="assets/images/svg/box.png" class="img-fluid blur-up lazyload"
                                                 alt="">
                                        </div>
                                        <div class="order-box-contain">
                                            <img src="assets/images/svg/box1.png" class="img-fluid blur-up lazyload"
                                                 alt="">
                                            <div>
                                                <h5 class="font-light">Tổng số đơn hàng</h5>
                                                <h3>{{ $totalOrders }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6">
                                    <div class="order-box">
                                        <div class="order-box-image">
                                            <img src="assets/images/svg/sent.png" class="img-fluid blur-up lazyload"
                                                 alt="">
                                        </div>
                                        <div class="order-box-contain">
                                            <img src="assets/images/svg/sent1.png" class="img-fluid blur-up lazyload"
                                                 alt="">
                                            <div>
                                                <h5 class="font-light">Đơn hàng chờ xử lý</h5>
                                                <h3>{{ $pendingOrders }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-sm-6">
                                    <div class="order-box">
                                        <div class="order-box-image">
                                            <img src="assets/images/svg/wishlist.png" class="img-fluid blur-up lazyload"
                                                 alt="">
                                        </div>
                                        <div class="order-box-contain">
                                            <img src="assets/images/svg/wishlist1.png" class="img-fluid blur-up lazyload"
                                                 alt="">
                                            <div>
                                                <h5 class="font-light">Danh sách yêu thích</h5>
                                                <h3>{{ $wishlistCount }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-account box-info">
                            <div class="box-head">
                                <h3>Thông tin tài khoản</h3>
                            </div>
                            <div class="row">
                                <!-- Thông tin liên hệ -->
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title d-flex justify-content-between align-items-center">
                                            <h4>Thông tin liên hệ</h4>
                                        </div>
                                        <div class="box-content">
                                            <h6 class="font-light">{{ Auth::user()->name }}</h6>
                                            <h6 class="font-light">{{ Auth::user()->email }}</h6>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sổ địa chỉ -->
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title d-flex justify-content-between align-items-center">
                                            <h4>Địa chỉ nhận hàng</h4>
                                        </div>
                                        <div class="box-content">
                                            <h6 class="font-light">Địa chỉ mặc định</h6>
                                            <h6 class="font-light">{{ $address->address  ?? null }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
