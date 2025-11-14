    <!--   Core JS Files   -->
    <script src="<?php echo url('/'); ?>/js/core/jquery.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/core/popper.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/core/bootstrap.bundle.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/plugin/chart.js/chart.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/plugin/chart-circle/circles.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/plugin/datatables/datatables.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/plugin/jsvectormap/world.js"></script>
    <script src="<?php echo url('/'); ?>/js/plugin/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="<?php echo url('/'); ?>/js/kaiadmin.min.js"></script>
    <script src="<?php echo url('/'); ?>/sw.js'"></script>
    <script>
    if (!navigator.serviceWorker.controller) {
        navigator.serviceWorker.register("/sw.js").then(function (reg) {
            console.log("Service worker registered: " + reg.scope);
        });
    }
    </script>