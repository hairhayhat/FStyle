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
                        <span class="badge rounded-pill badge-theme">{{ $notifications->count() }}</span>
                    </div>

                    <ul class="notification-dropdown onhover-show-div" style="max-height: 300px; overflow-y: auto;">
                        <li class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-2">
                            <h6 class="f-18 mb-0">
                                <span class="lnr lnr-alarm me-2"></span> Thông báo
                            </h6>
                            <a href="" class="small">Xem tất cả</a>
                        </li>

                        @forelse ($notifications as $item)
                            <li class="p-2 border-bottom">
                                <a href="{{ $item->link ?? '#' }}"
                                    class="d-flex justify-content-between text-decoration-none">
                                    <div>
                                        <small class="text-muted d-block">{{ $item->message }}</small>
                                    </div>
                                    <div class="text-end ms-2">
                                        <small class="text-muted">{{ $item->time_ago }}</small>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li>
                                <p class="text-center text-muted mb-0">Không có thông báo nào</p>
                            </li>
                        @endforelse
                    </ul>

                </li>

                <li>
                    <div class="mode">
                        <span class="lnr lnr-moon"></span>
                    </div>
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
                            <p class="mb-0 font-roboto">Admin</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
