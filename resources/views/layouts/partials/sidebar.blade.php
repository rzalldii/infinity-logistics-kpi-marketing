<div class="wrapper">
    <div class="sidebar sidebar-style-2" data-background-color="white">
        <div class="sidebar-logo">
            <div class="logo-header" data-background-color="white">
                <div class="logo d-flex justify-content-center w-100" href="{{ route('dashboard.index') }}">
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
                            <i class="fas fa-users"></i>
                            <p>Management Users</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>