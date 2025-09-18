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
                    <span class="lnr lnr-bubble"></span>
                    <span id="chatBadge" class="badge bg-danger">{{ $chatUsers->sum('new_count') }}</span>
                    <ul class="chat-dropdown onhover-show-div" id="chatPreviewDropdown"
                        data-user-id="{{ auth()->id() }}">
                        <li class="chat-title">
                            <span class="lnr lnr-bubble"></span>
                            <h6 class="f-18 mb-0">Tin nhắn</h6>
                        </li>
                        @foreach ($chatUsers as $item)
                            <li>
                                <div class="media" data-user="{{ $item['user']->id }}"
                                    data-user-name="{{ $item['user']->name }}">
                                    <img class="img-fluid rounded-circle me-3" src="{{ $item['user']->avatar }}"
                                        alt="{{ $item['user']->name }}">
                                    <div class="media-body">
                                        <span>{{ $item['user']->name }}</span>
                                        <p class="f-12 font-success">
                                            @if ($item['new_count'] > 0)
                                                +{{ $item['new_count'] }} tin nhắn mới
                                            @else
                                                Không có tin nhắn mới
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="onhover-dropdown">
                    <div class="notification-box">
                        <span class="lnr lnr-alarm"></span>
                        <span class="badge rounded-pill badge-theme"></span>
                    </div>

                    <ul class="notification-dropdown onhover-show-div" style="max-height: 300px; overflow-y: auto;">
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
                        <img class="user-profile rounded-circle" src="{{ asset(Auth::user()->avatar) }}"
                            alt="Avatar">
                        <div class="user-name-hide media-body">
                            <span>{{ Auth::user()->name }}</span>
                            <p class="mb-0 font-roboto">Admin</p>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div id="chat-container"></div>
    <div id="imageModal" class="image-modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modalImg">
    </div>
</div>
