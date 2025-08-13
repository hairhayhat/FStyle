<div class="sidebar-wrapper">
    <div>
        <div class="logo-wrapper logo-wrapper-center">
            <a href="index.html" data-bs-original-title="" title="">
                <img class="img-fluid for-dark" src="assets/images/logo/logo-white.png" alt="">
            </a>
            <div class="back-btn">
                <i class="fa fa-angle-left"></i>
            </div>
            <div class="toggle-sidebar">
                <i class="status_toggle middle sidebar-toggle" data-feather="grid"></i>
            </div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="index.html">
                <img class="img-fluid main-logo" src="assets/images/logo/logo.png" alt="logo">
            </a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow">
                <i data-feather="arrow-left"></i>
            </div>

            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"></li>
                    <li class="sidebar-main-title sidebar-main-title-3">
                        <div>
                            <h6 class="lan-1">General</h6>
                            <p class="lan-2">Dashboards &amp; Users.</p>
                        </div>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="index.html">
                            <i data-feather="home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>


                    <li class="sidebar-main-title sidebar-main-title-2">
                        <div>
                            <h6 class="lan-1">Application</h6>
                            <p class="lan-2">Ready To Use Apps</p>
                        </div>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i data-feather="archive"></i>
                            <span>Đơn hàng</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('admin.order.index') }}">Danh sách đơn hàng</a>
                            </li>
                            <li>
                                <a href="order-detail.html">Order Detail</a>
                            </li>
                            <li>
                                <a href="order-tracking.html">Order Tracking</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="sidebar-list {{ request()->is('admin/category') || request()->is('admin/category/*') ? 'active' : '' }}">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i data-feather="grid"></i>
                            <span>Danh mục</span>
                        </a>
                        <ul class="sidebar-submenu"
                            style="{{ request()->is('admin/category') || request()->is('admin/category/*') ? 'display: block;' : '' }}">
                            <li class="{{ request()->routeIs('admin.category.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.category.index') }}">Danh sách danh mục</a>
                            </li>
                            <li>
                                <a :active="request()->routeIs('admin.category.create')"
                                    href="{{ route('admin.category.create') }}">Thêm danh mục</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="sidebar-list {{ request()->is('admin/category') || request()->is('admin/category/*') ? 'active' : '' }}">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i data-feather="users"></i>
                            <span>Người dùng</span>
                        </a>
                        <ul class="sidebar-submenu"
                            style="{{ request()->is('admin/category') || request()->is('admin/category/*') ? 'display: block;' : '' }}">
                            <li class="{{ request()->routeIs('admin.category.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.users.index') }}">Danh sách người dùng</a>
                            </li>

                        </ul>
                    </li>
                    <li
                        class="sidebar-list {{ request()->is('admin/product') || request()->is('admin/product/*') ? 'active' : '' }}">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i data-feather="box"></i>
                            <span>Sản Phẩm</span>
                        </a>
                        <ul class="sidebar-submenu"
                            style="{{ request()->is('admin/product') || request()->is('admin/product/*') ? 'display: block;' : '' }}">
                            <li class="{{ request()->routeIs('admin.product.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.product.index') }}">Danh sách sản phẩm</a>
                            </li>
                            <li>
                                <a :active="request()->routeIs('admin.product.create')"
                                    href="{{ route('admin.product.create') }}">Thêm sản phẩm</a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="sidebar-list {{ request()->is('admin/voucher') || request()->is('admin/voucher/*') ? 'active' : '' }}">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i data-feather="tag"></i>
                            <span>Voucher</span>
                        </a>
                        <ul class="sidebar-submenu"
                            style="{{ request()->is('admin/voucher') || request()->is('admin/voucher/*') ? 'display: block;' : '' }}">
                            <li class="{{ request()->routeIs('admin.voucher.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.vouchers.index') }}">Danh sách voucher</a>
                            </li>
                            <li class="{{ request()->routeIs('admin.voucher.create') ? 'active' : '' }}">
                                <a href="{{ route('admin.vouchers.create') }}">Thêm voucher</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                            <i data-feather="settings"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('admin.profile.edit') }}">Profile Setting</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.color.index') }}">Màu sắc</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.size.index') }}">Kích thước</a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>
        </nav>
    </div>
</div>
