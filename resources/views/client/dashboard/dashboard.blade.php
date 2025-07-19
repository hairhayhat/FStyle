@extends('client.dashboard.layouts.app')

@section('content')
    <div class="col-lg-9">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="dash">
                <div class="dashboard-right">
                    <div class="dashboard">
                        <div class="page-title title title1 title-effect">
                            <h2>My Dashboard</h2>
                        </div>
                        <div class="welcome-msg">
                            <h6 class="font-light">Hello, <span>MARK JECNO !</span></h6>
                            <p class="font-light">From your My Account Dashboard you have the ability to
                                view a snapshot of your recent account activity and update your account
                                information. Select a link below to view or edit information.</p>
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
                                                <h5 class="font-light">total order</h5>
                                                <h3>3648</h3>
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
                                                <h5 class="font-light">pending orders</h5>
                                                <h3>215</h3>
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
                                                <h5 class="font-light">wishlist</h5>
                                                <h3>63874</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-account box-info">
                            <div class="box-head">
                                <h3>Account Information</h3>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h4>Contact Information</h4><a href="javascript:void(0)">Edit</a>
                                        </div>
                                        <div class="box-content">
                                            <h6 class="font-light">MARK JECNO</h6>
                                            <h6 class="font-light">MARk-JECNO@gmail.com</h6>
                                            <a href="javascript:void(0)">Change Password</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="box">
                                        <div class="box-title">
                                            <h4>Newsletters</h4><a href="javascript:void(0)">Edit</a>
                                        </div>
                                        <div class="box-content">
                                            <h6 class="font-light">You are currently not subscribed to any
                                                newsletter.</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="box address-box">
                                    <div class="box-title">
                                        <h4>Address Book</h4><a href="javascript:void(0)">Manage
                                            Addresses</a>
                                    </div>
                                    <div class="box-content">
                                        <div class="row g-4">
                                            <div class="col-sm-6">
                                                <h6 class="font-light">Default Billing Address</h6>
                                                <h6 class="font-light">You have not set a default
                                                    billing address.</h6>
                                                <a href="javascript:void(0)">Edit Address</a>
                                            </div>
                                            <div class="col-sm-6">
                                                <h6 class="font-light">Default Shipping Address</h6>
                                                <h6 class="font-light">You have not set a default
                                                    shipping address.</h6>
                                                <a href="javascript:void(0)">Edit Address</a>
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
    </div>
@endsection
