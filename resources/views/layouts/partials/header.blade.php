<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <meta name="theme-color" content="#6777ef">
    <link rel="apple-touch-icon" href="<?php echo url('/'); ?>/img/favicon.ico">
    <link rel="manifest" href="<?php echo url('/'); ?>/manifest.json">

    <!-- Fonts and icons -->
    <script src="<?php echo url('/'); ?>/js/plugin/webfont/webfont.min.js"></script>
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
            urls: ["<?php echo url('/'); ?>/css/fonts.min.css"],
            },
            active: function () {
            sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- Preload CSS Files -->
    <link href="<?php echo url('/'); ?>/css/bootstrap.min.css" rel="preload" as="style">
    <link href="<?php echo url('/'); ?>/css/plugins.min.css" rel="preload" as="style">
    <link href="<?php echo url('/'); ?>/css/kaiadmin.min.css" rel="preload" as="style">

    <!-- Stylesheet CSS Files -->
    <link href="<?php echo url('/'); ?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo url('/'); ?>/css/plugins.min.css" rel="stylesheet">
    <link href="<?php echo url('/'); ?>/css/kaiadmin.min.css" rel="stylesheet">
</head>