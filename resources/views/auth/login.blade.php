<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In | Key Perfomance Indicator Marketing</title>

    <meta name="theme-color" content="#6777ef">
    <link rel="apple-touch-icon" href="{{ url('/') }}/img/favicon.ico">
    <link rel="manifest" href="{{ url('/') }}/manifest.json">

    <!-- Favicons -->
    <link href="{{ url('/') }}/img/favicon.ico" rel="icon" alt="Icon Infinity">

    <!-- Fonts and icons -->
    <script src="{{ url('/') }}/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
        families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
            ],
            urls: ["{{ url('/') }}/css/fonts.min.css"],
            },
            active: function () {
            sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- Preload CSS Files -->
    <link href="{{ url('/') }}/css/bootstrap.min.css" rel="preload" as="style">
    <link href="{{ url('/') }}/css/plugins.min.css" rel="preload" as="style">
    <link href="{{ url('/') }}/css/kaiadmin.min.css" rel="preload" as="style">

    <!-- Stylesheet CSS Files -->
    <link href="{{ url('/') }}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/plugins.min.css" rel="stylesheet">
    <link href="{{ url('/') }}/css/kaiadmin.min.css" rel="stylesheet">
</head>
<body>
    <div class="container d-flex flex-column">
        <div class="row align-items-center justify-content-center g-0 min-vh-100">
            <div class="col-12 col-md-8 col-lg-6 col-xxl-4 py-8 py-xl-0">
                <div class="card smooth-shadow-md">
                    <div class="card-body p-6">
                        <div class="mb-4">
                            <img src="{{ url('/') }}/img/logo.png" width="150" class="d-block mx-auto" alt="">
                        </div>
                        <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                            @csrf
                            @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <div class="mb-3">
                                <label for="login" class="form-label">Name or Email</label>
                                <input type="text" name="login" class="form-control" value="{{ old('login') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div style="position: relative;">
                                    <input type="password" name="password" class="form-control" required>
                                    <i class="fa fa-eye" id="togglePassword" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                </div>
                            </div>
                            <div>
                                <div class="d-grid">
                                    <button id="loginBtn" type="submit" class="btn btn-primary">
                                        <i class="fas fa-sign-in-alt"></i> Sign in
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer bg-transparent text-center text-muted border-0">
                        <small>Copyright © 2025 <strong>PT. INFINITY LOGISTICS INDONESIA</strong> All Rights Reserved.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--   Core JS Files   -->
    <script src="{{ url('/') }}/js/core/jquery.min.js"></script>
    <script src="{{ url('/') }}/js/core/popper.min.js"></script>
    <script src="{{ url('/') }}/js/core/bootstrap.bundle.min.js"></script>
    <script src="{{ url('/') }}/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
    <script src="{{ url('/') }}/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="{{ url('/') }}/js/plugin/chart.js/chart.min.js"></script>
    <script src="{{ url('/') }}/js/plugin/chart-circle/circles.min.js"></script>
    <script src="{{ url('/') }}/js/plugin/datatables/datatables.min.js"></script>
    <script src="{{ url('/') }}/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="{{ url('/') }}/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="{{ url('/') }}/js/plugin/jsvectormap/world.js"></script>
    <script src="{{ url('/') }}/js/plugin/select2/select2.full.min.js"></script>
    <script src="{{ url('/') }}/js/plugin/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="{{ url('/') }}/js/kaiadmin.min.js"></script>
    <script src="{{ url('/') }}/sw.js'"></script>
    <script>
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function (reg) {
            console.log("Service worker registered: " + reg.scope);
        });
    }
    </script>
    <script>
    $(document).ready(function() {
        $('#togglePassword').on('click', function() {
            const passwordField = $('input[name="password"]');
            const eyeIcon = $(this);
            const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
            passwordField.attr('type', type);
            if (type === 'password') {
                eyeIcon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                eyeIcon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    });
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Invalid Credentials!',
            confirmButtonColor: '#d33'
        });
    @endif
    $('#loginForm').on('submit', function() {
        $('#loginBtn').html('<i class="fas fa-spinner fa-spin"></i> Signing in...').prop('disabled', true);
    });
    </script>
</body>
</html>