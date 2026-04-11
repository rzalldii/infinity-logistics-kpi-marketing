<div class="wrapper">
    <div class="sidebar" data-background-color="white">
        <div class="sidebar-logo">
            <div class="logo-header" data-background-color="white">
                <div class="logo d-flex justify-content-center w-100">
                    <img src="{{ url('/') }}/img/logo_light.png" class="navbar-brand" height="40" alt="PT. Infinity Logistics Indonesia"/>
                </div>
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
                    <li class="nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                        <a href="{{ route('dashboard.index') }}">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Marketing</h4>
                    </li>
                    <li class="nav-item {{ request()->routeIs('rates.*') ? 'active' : '' }}">
                        <a href="{{ route('rates.index') }}">
                            <i class="fas fa-dollar-sign"></i>
                            <p>Checking Rates</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('shippers.*') ? 'active' : '' }}">
                        <a href="{{ route('shippers.index') }}">
                            <i class="fas fa-ship"></i>
                            <p>Touch Shippers</p>
                        </a>
                    </li>
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isMarketing())
                    <li class="nav-item {{ request()->routeIs('activities.index', 'activities.edit') ? 'active' : '' }}">
                        <a href="{{ route('activities.index') }}">
                            <i class="fas fa-book-open"></i>
                            <p>Report Activities</p>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                    <li class="nav-item {{ request()->routeIs('activities.summaries*') ? 'active' : '' }}">
                        <a href="{{ route('activities.summaries') }}">
                            <i class="fas fa-chart-pie"></i>
                            <p>Summary Activities</p>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->isSuperAdmin())
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Admin</h4>
                    </li>
                    <li class="nav-item {{ request()->routeIs('audit.index') ? 'active' : '' }}">
                        <a href="{{ route('audit.index') }}">
                            <i class="fas fa-history"></i>
                            <p>Audit Logs</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">
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
                <div class="logo-header" data-background-color="white">
                    <div class="logo">
                        <img src="{{ url('/') }}/img/logo_light.png" class="navbar-brand" height="40" alt=""/>
                    </div>
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
            <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" data-background-color="white">
                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                        <li class="nav-item topbar-user dropdown hidden-caret">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                                <div class="profile-username text-dark">
                                    <span class="op-7">Hi,</span>
                                    <span class="fw-bold">{{ Auth::user()->name }}</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="u-text text-dark">
                                                <h4>{{ Auth::user()->role }}</h4>
                                                <p>{{ Auth::user()->email }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                        <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                                            @csrf
                                        </form>
                                        <a class="dropdown-item" href="#" id="logoutBtn">Logout</a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>