<header class="header-style-2" id="home">
    <div class="main-header navbar-searchbar">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-menu">
                        <div class="menu-left">
                            <div class="brand-logo">
                                <a href="{{ route('welcome') }}">
                                    <svg class="svg-icon">
                                        <use class="fill-color" xlink:href="client/assets/assets/svg/icons.svg#logo">
                                        </use>
                                    </svg>
                                    <img src="{{ asset('client/assets/images/logo/Logo.jpg') }}"
                                        class="img-fluid blur-up lazyload style-2" alt="logo"
                                        style="width: 80px; height: auto;">
                                </a>
                            </div>
                        </div>
                        <nav>
                            <div class="main-navbar">
                                <div id="mainnav">
                                    <div class="toggle-nav">
                                        <i class="fa fa-bars sidebar-bar"></i>
                                    </div>
                                    <ul class="nav-menu">
                                        <li class="back-btn d-xl-none">
                                            <div class="close-btn">
                                                Menu
                                                <span class="mobile-back"><i class="fa fa-angle-left"></i>
                                                </span>
                                            </div>
                                        </li>
                                        <li class="mega-menu home-menu">
                                            <a href="{{ route('welcome') }}" class="nav-link menu-title">Trang chủ</a>
                                        </li>

                                        <li class="dropdown">
                                            <a href="javascript:void(0)" class="nav-link menu-title">Danh mục</a>
                                            <ul class="nav-submenu menu-content">
                                                @foreach ($categories as $item)
                                                    <li>
                                                        <a
                                                            href="{{ route('search.category', ['slug' => $item->slug]) }}">{{ $item->name }}</a>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </li>
                                        <li class="mobile-poster d-flex d-xl-none">
                                            <img src="client/assets/assets/images/pwa.png" class="img-fluid"
                                                alt="">
                                            <div class="mobile-contain">
                                                <h5>Enjoy app-like experience</h5>
                                                <p class="font-light">With this Screen option you can use Website
                                                    like an App.</p>
                                                <a href="javascript:void(0)" id="installApp"
                                                    class="btn btn-solid-default btn-spacing w-100">ADD TO
                                                    HOMESCREEN</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                        <div class="menu-right">
                            <ul>
                                <li>
                                    <div class="search-box">
                                        <i data-feather="search"></i>
                                    </div>
                                </li>

                                <li class="onhover-dropdown">
                                    <div class="cart-media">
                                        @auth
                                            <img class="user-profile rounded-circle" src="{{ asset(Auth::user()->avatar) }}"
                                                alt="Avatar" class="rounded-circle"
                                                style="width:32px;height:32px;object-fit:cover;">
                                        @else
                                            <i data-feather="user"></i>
                                        @endauth
                                    </div>
                                    <div class="onhover-div profile-dropdown">
                                        <ul>
                                            @auth
                                                <li>
                                                    <a href="{{ route('client.dashboard') }}" class="d-block">Cài đặt</a>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <a href="{{ route('logout') }}"
                                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                                            class="d-block">Đăng xuất</a>
                                                    </form>
                                                </li>
                                            @else
                                                <li>
                                                    <a href="{{ route('login') }}" class="d-block">Đăng nhập</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('register') }}" class="d-block">Đăng ký</a>
                                                </li>
                                            @endauth
                                        </ul>
                                    </div>
                                </li>

                                <li class="message-dropdown">
                                    <div class="cart-media">
                                        <a href="#" id="messageToggle" class="message-icon">
                                            <i data-feather="message-circle"></i>
                                            <span class="label label-theme rounded-pill message-badge">0</span>
                                        </a>
                                    </div>
                                    <div class="message-menu" id="messageMenu">
                                        <div class="message-header">
                                            <h6>Tin nhắn</h6>
                                        </div>
                                        <div class="message-list">
                                            @foreach ($adminUsers as $item)
                                                <div class="media user-item" data-id="{{ $item->id }}"
                                                    data-name="{{ $item->name }}">
                                                    <img class="img-fluid rounded-circle me-3"
                                                        src="{{ $item->avatar ?? '/default.png' }}" alt="user">
                                                    <div class="media-body">
                                                        <span>{{ $item->name }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </li>

                                <li class="onhover-dropdown notification-dropdown">
                                    <div class="cart-media">
                                        <a href="#" class="notification-icon">
                                            <i data-feather="bell"></i>
                                            <span class="label label-theme rounded-pill notification-badge">0</span>
                                        </a>
                                    </div>
                                    <div class="onhover-div notification-menu">
                                        <div class="notification-header">
                                            <i class="lnr lnr-alarm"></i>
                                            <h6>Thông báo</h6>
                                        </div>
                                        <div class="notification-list">
                                        </div>
                                        <div class="notification-footer">
                                            <a href="/admin/notifications" class="btn btn-solid-default w-100">Kiểm
                                                tra
                                                toàn
                                                bộ thông báo</a>
                                        </div>
                                    </div>
                                </li>

                                <li class="onhover-dropdown cart-dropdown">
                                    <a href="{{ route('client.cart') }}" class="btn btn-solid-default btn-spacing">
                                        <i data-feather="shopping-cart"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="search-full">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i data-feather="search" class="font-light"></i>
                                </span>
                                <input type="text" id="global-search"
                                    data-search-url="{{ route('search.ajax.products') }}"
                                    class="form-control search-type" placeholder="Tìm kiếm ở đây..">
                                <span class="input-group-text close-search">
                                    <i data-feather="x" class="font-light"></i>
                                </span>
                            </div>
                            <div class="search-suggestion">
                                <ul class="custom-scroll" id="search-results">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="chat-container"></div>
        </div>
    </div>
</header>
