<div class="wrapper">
    <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
            <div class="logo-header" data-background-color="dark">
                <!-- <div class="logo">
                    <img src="<?php echo url('/'); ?>/img/logo.png" class="navbar-brand" height="20" alt=""/>
                </div> -->
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-primary">
                    <li class="nav-item">
                        <a href="/">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Sections</h4>
                    </li>
                    <li class="nav-item">
                        <a href="/rates">
                            <i class="fas fa-dollar-sign"></i>
                            <p>Checking Rates</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/shippers">
                            <i class="fas fa-list-ul"></i>
                            <p>Touch Shippers</p>
                        </a>
                    </li>
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                    <li class="nav-item">
                        <a href="/activities">
                            <i class="fas fa-book-open"></i>
                            <p>Report Activities</p>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->isSuperAdmin())
                    <li class="nav-item">
                        <a href="/users">
                            <i class="fas fa-user"></i>
                            <p>User Management</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="main-panel">
        <div class="main-header">
            <div class="main-header-logo">
                <div class="logo-header" data-background-color="dark">
                    <!-- <div class="logo">
                        <img src="<?php echo url('/'); ?>/img/logo.png" class="navbar-brand" height="20" alt=""/>
                    </div> -->
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
            </div>
            <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" data-background-color="dark">
                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                        <li class="nav-item topbar-user dropdown hidden-caret">
                            <a class="dropdown-toggle" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="profile-username text-white">
                                    <span class="op-7">Hi,</span>
                                    <span class="fw-bold">{{ Auth::user()->name }}</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="u-text text-white">
                                                <h4 class="text-uppercase">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</h4>
                                                <p>{{ Auth::user()->email }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>