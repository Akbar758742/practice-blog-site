<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8" />
    <title>@yield('pageTitle', isset($pageTitle) ? $pageTitle : 'Blog Admin')</title>

    <!-- Site favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ \App\Helpers\Settings::favicon() }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ \App\Helpers\Settings::favicon() }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ \App\Helpers\Settings::favicon() }}" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @kropifyStyles

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('backend') }}/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend') }}/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('backend') }}/vendors/styles/style.css" />

    @stack('styesheets')
</head>

<body>
    {{-- <div class="pre-loader">
        <div class="pre-loader-box">
            <div class="loader-logo">
                <img src="{{asset('backend')}}/vendors/images/deskapp-logo.svg" alt="" />
            </div>
            <div class="loader-progress" id="progress_div">
                <div class="bar" id="bar1"></div>
            </div>
            <div class="percent" id="percent1">0%</div>
            <div class="loading-text">Loading...</div>
        </div>
    </div> --}}

    <div class="header">
        <div class="header-left">
            <div class="menu-icon bi bi-list"></div>
            <div class="search-toggle-icon bi bi-search" data-toggle="header_search"></div>
            <div class="header-search">
                <form>
                    <div class="form-group mb-0">
                        <i class="dw dw-search2 search-icon"></i>
                        <input type="text" class="form-control search-input" placeholder="Search Here" />
                        <div class="dropdown">
                            <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                                <i class="ion-arrow-down-c"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">From</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input class="form-control form-control-sm form-control-line" type="text" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">To</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input class="form-control form-control-sm form-control-line" type="text" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-12 col-md-2 col-form-label">Subject</label>
                                    <div class="col-sm-12 col-md-10">
                                        <input class="form-control form-control-sm form-control-line" type="text" />
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="header-right">
            <div class="dashboard-setting user-notification">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                        <i class="dw dw-settings2"></i>
                    </a>
                </div>
            </div>
            <div class="user-notification">
                <div class="dropdown">
                    <a class="dropdown-toggle no-arrow" href="#" role="button" data-toggle="dropdown">
                        <i class="icon-copy dw dw-notification"></i>
                        <span class="badge notification-active"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="notification-list mx-h-350 customscroll">
                            <ul>
                                <li>
                                    <a href="#">
                                        <img src="{{ asset('backend') }}/vendors/images/img.jpg" alt="" />
                                        <h3>John Doe</h3>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing
                                            elit, sed...
                                        </p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="{{ asset('backend') }}/vendors/images/photo1.jpg" alt="" />
                                        <h3>Lea R. Frith</h3>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing
                                            elit, sed...
                                        </p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="{{ asset('backend') }}/vendors/images/photo2.jpg" alt="" />
                                        <h3>Erik L. Richards</h3>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing
                                            elit, sed...
                                        </p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="{{ asset('backend') }}/vendors/images/photo3.jpg" alt="" />
                                        <h3>John Doe</h3>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing
                                            elit, sed...
                                        </p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="{{ asset('backend') }}/vendors/images/photo4.jpg" alt="" />
                                        <h3>Renee I. Hansen</h3>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing
                                            elit, sed...
                                        </p>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <img src="{{ asset('backend') }}/vendors/images/img.jpg" alt="" />
                                        <h3>Vicki M. Coleman</h3>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing
                                            elit, sed...
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @livewire('admin.top-user-info')
            <div class="github-link">
                <a href="https://github.com/dropways/deskapp" target="_blank"><img
                        src="{{ asset('backend') }}/vendors/images/github.svg" alt="" /></a>
            </div>
        </div>
    </div>

    <div class="right-sidebar">
        <div class="sidebar-title">
            <h3 class="weight-600 font-16 text-blue">
                Layout Settings
                <span class="btn-block font-weight-400 font-12">User Interface Settings</span>
            </h3>
            <div class="close-sidebar" data-toggle="right-sidebar-close">
                <i class="icon-copy ion-close-round"></i>
            </div>
        </div>
        <div class="right-sidebar-body customscroll">
            <div class="right-sidebar-body-content">
                <h4 class="weight-600 font-18 pb-10">Header Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
                <div class="sidebar-btn-group pb-30 mb-10">
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">White</a>
                    <a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
                <div class="sidebar-radio-group pb-10 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input"
                            value="icon-style-1" checked="" />
                        <label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-2" name="menu-dropdown-icon" class="custom-control-input"
                            value="icon-style-2" />
                        <label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebaricon-3" name="menu-dropdown-icon" class="custom-control-input"
                            value="icon-style-3" />
                        <label class="custom-control-label" for="sidebaricon-3"><i
                                class="fa fa-angle-double-right"></i></label>
                    </div>
                </div>

                <h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
                <div class="sidebar-radio-group pb-30 mb-10">
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-1" name="menu-list-icon" class="custom-control-input"
                            value="icon-list-style-1" checked="" />
                        <label class="custom-control-label" for="sidebariconlist-1"><i
                                class="ion-minus-round"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-2" name="menu-list-icon" class="custom-control-input"
                            value="icon-list-style-2" />
                        <label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o"
                                aria-hidden="true"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-3" name="menu-list-icon" class="custom-control-input"
                            value="icon-list-style-3" />
                        <label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-4" name="menu-list-icon" class="custom-control-input"
                            value="icon-list-style-4" checked="" />
                        <label class="custom-control-label" for="sidebariconlist-4"><i
                                class="icon-copy dw dw-next-2"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-5" name="menu-list-icon" class="custom-control-input"
                            value="icon-list-style-5" />
                        <label class="custom-control-label" for="sidebariconlist-5"><i
                                class="dw dw-fast-forward-1"></i></label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="sidebariconlist-6" name="menu-list-icon" class="custom-control-input"
                            value="icon-list-style-6" />
                        <label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
                    </div>
                </div>

                <div class="reset-options pt-30 text-center">
                    <button class="btn btn-danger" id="reset-settings">
                        Reset Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="left-side-bar">
        <div class="brand-logo">
            <a href="index.html">
                <img src="{{ \App\Helpers\Settings::logo() }}" alt="{{ \App\Helpers\Settings::siteName() }}" class="dark-logo" style="max-height:45px;" />
                <img src="{{ \App\Helpers\Settings::logoWhite() }}" alt="{{ \App\Helpers\Settings::siteName() }}" class="light-logo" style="max-height:45px;" />
            </a>
            <div class="close-sidebar" data-toggle="left-sidebar-close">
                <i class="ion-close-round"></i>
            </div>
        </div>
        <div class="menu-block customscroll">
            <div class="sidebar-menu">
                <ul id="accordion-menu">

                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-home"></span><span class="mtext">home</span>
                        </a>
                    </li>

                    @if(auth()->user()->hasPermission('category.manage'))
                        <li>
                            <a href="{{ route('admin.categories') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.categories') ? 'active' : '' }}">
                                <span class="micon fa fa-clone"></span><span class="mtext">Categories</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->hasPermission('post.create') || auth()->user()->hasPermission('post.edit'))
                        <li class="dropdown">
                            <a href="javascript:;" class="dropdown-toggle {{ Route::is('admin.posts.*') ? 'active' : '' }}">
                                <span class="micon fa fa-newspaper-o"></span><span class="mtext">Posts</span>
                            </a>
                            <ul class="submenu">
                                <li><a href="{{ route('admin.posts.index') }}"
                                        class="{{ Route::is('admin.posts.index') ? 'active' : '' }}">All Posts</a></li>
                                @if(auth()->user()->hasPermission('post.create'))
                                    <li><a href="{{ route('admin.posts.create') }}"
                                            class="{{ Route::is('admin.posts.create') ? 'active' : '' }}">Add New</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    @if(auth()->user()->hasPermission('tag.manage'))
                        <li>
                            <a href="{{ route('admin.tags') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.tags') ? 'active' : '' }}">
                                <span class="micon fa fa-tags"></span><span class="mtext">Tags</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->hasPermission('comment.view') || auth()->user()->hasPermission('comment.moderate'))
                        <li>
                            <a href="{{ route('admin.comments') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.comments') ? 'active' : '' }}">
                                <span class="micon fa fa-comments-o"></span><span class="mtext">Comments</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->hasRole('admin'))
                        <li>
                            <a href="{{ route('admin.pages.index') }}"
                                class="dropdown-toggle no-arrow {{ Route::is('admin.pages.*') ? 'active' : '' }}">
                                <span class="micon fa fa-file"></span><span class="mtext">Pages</span>
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="invoice.html" class="dropdown-toggle no-arrow">
                            <span class="micon bi bi-receipt-cutoff"></span><span class="mtext">Invoice</span>
                        </a>
                    </li>

                    @if(auth()->user()->hasPermission('user.manage') || auth()->user()->hasPermission('role.manage'))
                        <li class="dropdown">
                            <a href="javascript:;"
                                class="dropdown-toggle {{ Route::is('admin.users') || Route::is('admin.roles') || Route::is('admin.permissions') ? 'active' : '' }}">
                                <span class="micon fa fa-users"></span><span class="mtext">User Management</span>
                            </a>
                            <ul class="submenu">
                                @if(auth()->user()->hasPermission('user.manage'))
                                    <li><a href="{{ route('admin.users') }}"
                                            class="{{ Route::is('admin.users') ? 'active' : '' }}">All Users</a></li>
                                @endif
                                @if(auth()->user()->hasPermission('role.manage'))
                                    <li><a href="{{ route('admin.roles') }}"
                                            class="{{ Route::is('admin.roles') ? 'active' : '' }}">Roles</a></li>
                                    <li><a href="{{ route('admin.permissions') }}"
                                            class="{{ Route::is('admin.permissions') ? 'active' : '' }}">Permissions</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <div class="sidebar-small-cap">settings</div>
                    </li>

                    <li>
                        <a href="{{ route('admin.profile') }}" class="dropdown-toggle no-arrow">
                            <span class="micon fa fa-user-circle"></span>
                            <span class="mtext">profile
                            </span>
                        </a>
                    </li>

                    @if(auth()->user()->hasPermission('settings.manage'))
                        <li>
                            <a href="{{ route('admin.settings') }}" class="dropdown-toggle no-arrow">
                                <span class="micon fa fa-cogs"></span>
                                <span class="mtext">general
                                </span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="mobile-menu-overlay"></div>

    <div class="main-container">
        <div class="pd-ltr-20 xs-pd-20-10">
            <div class="min-height-200px">

                <div class="">
                    @if(isset($slot))
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endif
                </div>
            </div>
            <div class="footer-wrap pd-20 mb-20 card-box">
                DeskApp - Bootstrap 4 Admin Template By
                <a href="https://github.com/dropways" target="_blank">Ankit Hingarajiya</a>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="{{ asset('backend') }}/vendors/scripts/core.js"></script>
    <script src="{{ asset('backend') }}/vendors/scripts/script.min.js"></script>
    <script src="{{ asset('backend') }}/vendors/scripts/process.js"></script>
    <script src="{{ asset('backend') }}/vendors/scripts/layout-settings.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @kropifyScripts
    <!-- Global SweetAlert Handler for Livewire -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal:success', (data) => {
                const message = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    // title: message.title || 'Success',
                    text: message.message || '',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });

            Livewire.on('swal:error', (data) => {
                const message = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    // title: message.title || 'Error',
                    text: message.message || '',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            });

            Livewire.on('swal:warning', (data) => {
                const message = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'warning',
                    // title: message.title || 'Warning',
                    text: message.message || '',
                    showConfirmButton: false,
                    timer: 3500,
                    timerProgressBar: true
                });
            });

            // Close Bootstrap modals on Livewire event
            Livewire.on('close-modal', () => {
                // Close all open Bootstrap modals
                $('.modal').modal('hide');
                // Remove backdrop if stuck
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });
        });
    </script>
    @stack('scripts')

</body>

</html>
