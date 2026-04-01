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
    <script src="{{ url('/') }}/sw.js"></script>
    <script>
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function(reg) {
            console.log("Service Worker Registered:", reg.scope);
        });
    }
    </script>
    <script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    $(document).ready(function() {
        @if(session('toast_success'))
            Toast.fire({ icon: 'success', title: '{{ session('toast_success') }}' });
        @endif
        $('#logoutBtn').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Logout?',
                text: 'You will be redirected to the login page.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Cancel',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logoutForm').submit();
                }
            });
        });
    });
    </script>