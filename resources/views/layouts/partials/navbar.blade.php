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
                                <div class="profile-username text-dark d-flex align-items-center gap-2">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="fw-bold d-none d-lg-inline">{{ Auth::user()->name }}</span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer text-dark">
                                    <li>
                                        <div class="user-box">
                                            <div class="u-text">
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
                                        <a class="dropdown-item" href="#" id="logoutBtn">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>