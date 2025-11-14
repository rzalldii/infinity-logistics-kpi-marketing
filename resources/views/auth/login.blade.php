<!DOCTYPE html>
<html>

@include('layouts.partials.header')

<body>
    <div class="container d-flex flex-column">
        <div class="row align-items-center justify-content-center g-0 min-vh-100">
            <div class="col-12 col-md-8 col-lg-6 col-xxl-4 py-8 py-xl-0">
                <div class="card smooth-shadow-md">
                    <div class="card-body p-6">
                        <div class="mb-4">
                            <img src="<?php echo url('/'); ?>/img/logo.png" width="150" class="d-block mx-auto" alt="">
                        </div>
                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            @if(session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <div class="mb-3">
                                <label for="login" class="form-label">Name or Email</label>
                                <input type="text" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" required>
                                @error('login')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div style="position: relative;">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    <i class="fa fa-eye" id="togglePassword" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer;"></i>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Sign in</button>
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

    @include('layouts.partials.script')
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
    </script>
</body>
</html>