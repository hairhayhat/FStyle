<div class="page-header">
    <div class="header-wrapper row m-0">
        <div class="header-logo-wrapper col-auto p-0">
            <div class="logo-wrapper">
                <a href="index.html">
                    <img class="img-fluid main-logo" src="{{ asset('admin/assets/images/logo/logo.png') }}" alt="logo">
                    <img class="img-fluid white-logo" src="{{ asset('admin/assets/images/logo/logo-white.png') }}"
                        alt="logo">
                </a>
            </div>
            <div class="toggle-sidebar">
                <i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i>
            </div>
        </div>

        <form class="form-inline search-full col" action="javascript:void(0)" method="get">
            <div class="form-group w-100">
                <div class="Typeahead Typeahead--twitterUsers">
                    <div class="u-posRelative">
                        <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                            placeholder="Search Voxo .." name="q" title="" autofocus>
                        <i class="close-search" data-feather="x"></i>
                        <div class="spinner-border Typeahead-spinner" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="Typeahead-menu"></div>
                </div>
            </div>
        </form>
        <div class="nav-right col-4 pull-right right-header p-0">
            <ul class="nav-menus">
                <li>
                    <span class="header-search">
                        <span class="lnr lnr-magnifier"></span>
                    </span>
                </li>
                <li class="onhover-dropdown">
                    <div class="notification-box">
                        <span class="lnr lnr-alarm"></span>
                        <span class="badge rounded-pill badge-theme">4</span>
                    </div>
                    <ul class="notification-dropdown onhover-show-div">
                        <li>
                            <span class="lnr lnr-alarm"></span>
                            <h6 class="f-18 mb-0">Notitications</h6>
                        </li>
                        <li>
                            <p>
                                <i class="fa fa-circle-o me-3 font-primary"></i>Delivery processing <span
                                    class="pull-right">10
                                    min.</span>
                            </p>
                        </li>
                        <li>
                            <p>
                                <i class="fa fa-circle-o me-3 font-success"></i>Order Complete<span class="pull-right">1
                                    hr</span>
                            </p>
                        </li>
                        <li>
                            <p>
                                <i class="fa fa-circle-o me-3 font-info"></i>Tickets Generated<span class="pull-right">3
                                    hr</span>
                            </p>
                        </li>
                        <li>
                            <p>
                                <i class="fa fa-circle-o me-3 font-danger"></i>Delivery Complete<span
                                    class="pull-right">6 hr</span>
                            </p>
                        </li>
                        <li>
                            <a class="btn btn-primary" href="javascript:void(0)">Check all notification</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <div class="mode">
                        <span class="lnr lnr-moon"></span>
                    </div>
                </li>

                <li class="onhover-dropdown">
                    <span class="lnr lnr-bubble"></span>
                    <ul class="chat-dropdown onhover-show-div">
                        <li>
                            <span class="lnr lnr-bubble"></span>
                            <h6 class="f-18 mb-0">Message Box</h6>
                        </li>
                        <li>
                            <div class="media">
                                <img class="img-fluid rounded-circle me-3" src="admin/assets/images/user/1.jpg"
                                    alt="user1">
                                <div class="status-circle online"></div>
                                <div class="media-body">
                                    <span>Erica Hughes</span>
                                    <p>Lorem Ipsum is simply dummy...</p>
                                </div>
                                <p class="f-12 font-success">58 mins ago</p>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <img class="img-fluid rounded-circle me-3" src="admin/assets/images/user/2.png"
                                    alt="user2">
                                <div class="status-circle online"></div>
                                <div class="media-body">
                                    <span>Kori Thomas</span>
                                    <p>Lorem Ipsum is simply dummy...</p>
                                </div>
                                <p class="f-12 font-success">1 hr ago</p>
                            </div>
                        </li>
                        <li>
                            <div class="media">
                                <img class="img-fluid rounded-circle me-3" src="admin/assets/images/user/3.png"
                                    alt="user3">
                                <div class="status-circle offline"></div>
                                <div class="media-body">
                                    <span>Ain Chavez</span>
                                    <p>Lorem Ipsum is simply dummy...</p>
                                </div>
                                <p class="f-12 font-danger">32 mins ago</p>
                            </div>
                        </li>
                        <li class="text-center">
                            <a class="btn btn-primary" href="javascript:void(0)">View All</a>
                        </li>
                    </ul>
                </li>

                <li class="maximize">
                    <a class="text-dark" href="javascript:void(0)!" onclick="javascript:toggleFullScreen()">
                        <span class="lnr lnr-frame-expand"></span>
                    </a>
                </li>
                <li class="profile-nav onhover-dropdown pe-0 me-0">
                    <div class="media profile-media">
                        <img class="user-profile rounded-circle" src="admin/assets/images/users/4.jpg" alt="">
                        <div class="user-name-hide media-body">
                            <span>Emay Walter</span>
                            <p class="mb-0 font-roboto">Admin<i class="middle fa fa-angle-down"></i></p>
                        </div>
                    </div>
                    <ul class="profile-dropdown onhover-show-div">
                        <li>
                            <a href="all-users.html">
                                <i data-feather="users"></i>
                                <span>Users</span>
                            </a>
                        </li>
                        <li>
                            <a href="order-list.html">
                                <i data-feather="archive"></i>
                                <span>Orders</span>
                            </a>
                        </li>
                        <li>
                            <a href="support-ticket.html">
                                <i data-feather="phone"></i>
                                <span>Spports Tickets</span>
                            </a>
                        </li>
                        <li>
                            <a href="profile-setting.html">
                                <i data-feather="settings"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li>
                            <a data-bs-toggle="modal" data-bs-target="#staticBackdrop" href="javascript:void(0)">
                                <i data-feather="log-out"></i>
                                <span>Log out</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
