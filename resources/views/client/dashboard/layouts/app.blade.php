<!DOCTYPE html>
<html lang="en">

@include('client.partials.head')

<body class="theme-color2 light ltr">

    <!-- header start -->
    @include('client.partials.header')
    <!-- header end -->

    <!-- mobile fix menu start -->
    <div class="mobile-menu d-sm-none">
        <ul>
            <li>
                <a href="index.html">
                    <i data-feather="home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="toggle-category">
                    <i data-feather="align-justify"></i>
                    <span>Category</span>
                </a>
            </li>
            <li>
                <a href="cart.html">
                    <i data-feather="shopping-bag"></i>
                    <span>Cart</span>
                </a>
            </li>
            <li>
                <a href="wishlist.html">
                    <i data-feather="heart"></i>
                    <span>Wishlist</span>
                </a>
            </li>
            <li>
                <a href="user-dashboard.html" class="active">
                    <i data-feather="user"></i>
                    <span>Account</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- mobile fix menu end -->

    <!-- user dashboard section start -->
    <section class="section-b-space">
        <div class="container">
            <div class="row">

                @include('client.dashboard.layouts.sidebar')

                @yield('content')

            </div>
        </div>
    </section>
    <!-- user dashboard section end -->

    <!-- Subscribe Section Start -->
    <section class="subscribe-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-6">
                    <div class="subscribe-details">
                        <h2 class="mb-3">Subscribe Our News</h2>
                        <h6 class="font-light">Subscribe and receive our newsletters to follow the news about our fresh
                            and fantastic Products.</h6>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mt-md-0 mt-3">
                    <div class="subsribe-input">
                        <div class="input-group">
                            <input type="text" class="form-control subscribe-input" placeholder="Your Email Address">
                            <button class="btn btn-solid-default" type="button">Button</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Subscribe Section End -->

    <!-- footer start -->
    @include('client.partials.footer')
    <!-- footer end -->

    <!-- Reset Password Modal Start -->
    <div class="modal fade reset-email-modal" id="resetEmail">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Comfirm Email</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <form>
                        <div class="mb-3">
                            <label for="email" class="form-label font-light">Email address</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="comfirmEmail" class="form-label font-light">Comfirm Email address</label>
                            <input type="email" class="form-control" id="comfirmEmail">
                        </div>
                        <div>
                            <label for="exampleInputPassword1" class="form-label font-light">Password</label>
                            <input type="password" class="form-control" id="exampleInputPassword1">
                        </div>
                    </form>
                </div>
                <div class="modal-footer pt-0">
                    <button class="btn bg-secondary rounded-1 modal-close-button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-solid-default rounded-1" data-bs-dismiss="modal">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Reset Password Modal End -->

    <!-- Add Payment Modal Start -->
    <div class="modal fade payment-modal" id="addPayment">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <label for="name" class="form-label">Card Type</label>
                        <select class="form-select form-select-lg mb-4">
                            <option selected disabled>Choose Your Card</option>
                            <option value="1">Creadit Card</option>
                            <option value="2">Debit Card</option>
                            <option value="3">Debit Card and ATM</option>
                        </select>

                        <div class="mb-4">
                            <label for="card" class="form-label">Name On Card</label>
                            <input type="text" class="form-control" id="card" placeholder="Name card">
                        </div>
                        <div class="mb-4">
                            <label for="cAddress" class="form-label">Card Number</label>
                            <input type="number" class="form-control" id="cAddress"
                                placeholder="XXXX-XXXX-XXXX-XXXX">
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-4">
                                <label for="expiry" class="form-label">Expiry Date</label>
                                <input type="date" class="form-control font-light" id="expiry">
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="cvv" class="form-label">CVV</label>
                                <input type="number" class="form-control" id="cvv" placeholder="XX9">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer pt-0 text-end d-block">
                    <button type="button" class="btn bg-secondary text-white rounded-1 modal-close-button"
                        data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-solid-default rounded-1" data-bs-dismiss="modal">Save Card
                        Details</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Payment Modal End -->

    <!-- Comfirm Delete Modal Start -->
    <div class="modal delete-account-modal fade" id="deleteModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pb-3 text-center mt-4">
                    <h4>Are you sure you want to delete your account?</h4>
                </div>
                <div class="modal-footer d-block text-center mb-4">
                    <button class="btn btn-solid-default btn-sm fw-bold rounded" data-bs-target="#doneModal"
                        data-bs-toggle="modal" data-bs-dismiss="modal">Yes Delete account</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal delete-account-modal fade" id="doneModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pb-3 text-center mt-4">
                    <h4>Done!!! Delete Your Account</h4>
                </div>
                <div class="modal-footer d-block text-center mb-4">
                    <button class="btn btn-solid-default btn-sm fw-bold rounded" data-bs-target="#exampleModalToggle"
                        data-bs-toggle="modal" data-bs-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Comfirm Delete Modal End -->

    <!-- tap to top Section Start -->
    <div class="tap-to-top">
        <a href="#home">
            <i class="fas fa-chevron-up"></i>
        </a>
    </div>
    <!-- tap to top Section End -->

    <div class="bg-overlay"></div>

    @include('client.partials.scripts')

</body>


<!-- Mirrored from themes.pixelstrap.com/voxo/front-end/user-dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Nov 2024 03:42:20 GMT -->

</html>
