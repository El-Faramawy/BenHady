<!doctype html>
<html lang="en" dir="rtl">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Yoha –  HTML5 Bootstrap Admin Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin dashboard html template, admin dashboard template bootstrap 4, analytics dashboard templates, best admin template bootstrap 4, best bootstrap admin template, bootstrap 4 template admin, bootstrap admin template premium, bootstrap admin ui, bootstrap basic admin template, cool admin template, dark admin dashboard, dark admin template, dark dashboard template, dashboard template bootstrap 4, ecommerce dashboard template, html5 admin template, light bootstrap dashboard, sales dashboard template, simple dashboard bootstrap 4, template bootstrap 4 admin">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{get_file(setting()->fav_icon)}}" />

    <!-- TITLE -->
    <title>{{setting()->name}} –  تسجيل الدخول</title>

    <!-- BOOTSTRAP CSS -->
    <link href="{{url('Admin')}}/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- STYLE CSS -->
    <link href="{{url('Admin')}}/assets/css-rtl/style.css" rel="stylesheet"/>
    <link href="{{url('Admin')}}/assets/css-rtl/skin-modes.css" rel="stylesheet"/>
    <link href="{{url('Admin')}}/assets/css-rtl/dark-style.css" rel="stylesheet"/>

    <!-- CUSTOM SCROLL BAR CSS-->
    <link href="{{url('Admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet"/>

    <!--- FONT-ICONS CSS -->
    <link href="{{url('Admin')}}/assets/css/icons.css" rel="stylesheet"/>

    <!-- INTERNAL SINGLE-PAGE CSS -->
    <link href="{{url('Admin')}}/assets/plugins/single-page/css/main.css" rel="stylesheet" type="text/css">

    <!-- COLOR SKIN CSS -->
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="{{url('Admin')}}/assets/colors/color1.css" />

    <link rel="stylesheet" type="text/css" href="{{url('Admin')}}/assets/plugins/toastify/toastify.min.css">

    <!-- GOOGLE FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body, input, button, select, textarea, h1, h2, h3, h4, h5, h6, p, a, label, .login100-form-title {
            font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
        }

        .fa {
            font-family: FontAwesome !important;
        }

        body.app {
            background: linear-gradient(135deg, #F5FDEA 0%, #EDF8DE 50%, #E2F2CD 100%) !important;
            min-height: 100vh;
        }

        .login-img {
            background: linear-gradient(135deg, #F5FDEA 0%, #EDF8DE 50%, #E2F2CD 100%) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container-login100 {
            background: transparent !important;
        }

        .wrap-login100 {
            background: #ffffff !important;
            border-radius: 20px !important;
            box-shadow: 0 16px 40px rgba(46, 90, 28, 0.12) !important;
            border: 1px solid #D8F2B5 !important;
            width: 440px !important;
            padding: 40px 36px !important;
            margin: 0 auto;
        }

        .col-login .header-brand-img {
            max-height: 65px;
            margin-bottom: 24px;
            filter: drop-shadow(0 4px 10px rgba(46, 90, 28, 0.15));
        }

        .login100-form-title {
            color: #1E3A1A !important;
            font-size: 24px !important;
            font-weight: 800 !important;
            margin-bottom: 24px !important;
            display: block;
            text-align: center;
        }

        .wrap-input100 {
            position: relative;
            width: 100%;
            z-index: 1;
            margin-bottom: 18px;
        }

        .input100 {
            font-size: 14px !important;
            line-height: 1.5 !important;
            color: #1E3A1A !important;
            display: block;
            width: 100%;
            background: #FAFCF7 !important;
            height: 48px !important;
            border-radius: 10px !important;
            padding: 0 45px 0 20px !important;
            border: 1.5px solid #D8E6C5 !important;
            transition: all 0.25s ease;
        }

        .input100:focus {
            background: #ffffff !important;
            border-color: #76C419 !important;
            box-shadow: 0 0 0 3px rgba(118, 196, 25, 0.2) !important;
            outline: none !important;
        }

        .symbol-input100 {
            font-size: 16px;
            display: flex;
            align-items: center;
            position: absolute;
            border-radius: 10px;
            bottom: 0;
            right: 0;
            left: auto;
            width: 45px;
            height: 100%;
            padding-right: 14px;
            padding-left: 0;
            pointer-events: none;
            color: #76C419 !important;
            border: none !important;
            transition: all 0.3s;
        }

        .symbol-input100 i {
            font-size: 18px !important;
            color: #76C419 !important;
            transition: color 0.25s ease;
        }

        .input100:focus + .focus-input100 + .symbol-input100 i {
            color: #2E5A1C !important;
        }

        .login100-form-btn {
            background: linear-gradient(135deg, #76C419 0%, #5ea312 100%) !important;
            border: none !important;
            color: #ffffff !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            height: 48px !important;
            border-radius: 10px !important;
            box-shadow: 0 4px 14px rgba(118, 196, 25, 0.38) !important;
            transition: all 0.25s ease-in-out !important;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login100-form-btn:hover {
            background: linear-gradient(135deg, #68b015 0%, #4f8b0e 100%) !important;
            box-shadow: 0 6px 18px rgba(118, 196, 25, 0.5) !important;
            transform: translateY(-1px);
        }

        /* Global Loader */
        #global-loader {
            position: fixed;
            z-index: 50000 !important;
            background: rgba(255, 255, 255, 0.9) !important;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            height: 100%;
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }

        .loader-img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 75px;
            height: 75px;
        }
    </style>
</head>

<body class="app sidebar-mini">

<!-- BACKGROUND-IMAGE -->
<div class="login-img">

    <!-- GLOABAL LOADER -->
    <div id="global-loader">
        <img src="{{url('Admin')}}/assets/images/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- End GLOABAL LOADER -->
    <!-- PAGE -->
    <div class="page">
        <div class="">
            @yield('content')
        </div>
    </div>
    <!-- END PAGE -->
</div>
<!-- BACKGROUND-IMAGE CLOSED -->
<!-- JQUERY JS -->
<script src="{{url('Admin')}}/assets/js/jquery-3.4.1.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="{{url('Admin')}}/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{url('Admin')}}/assets/plugins/bootstrap/js/popper.min.js"></script>

<!-- SPARKLINE JS-->
<script src="{{url('Admin')}}/assets/js/jquery.sparkline.min.js"></script>

<!-- CHART-CIRCLE JS-->
<script src="{{url('Admin')}}/assets/js/circle-progress.min.js"></script>

<!-- RATING STARJS -->
<script src="{{url('Admin')}}/assets/plugins/rating/jquery.rating-stars.js"></script>

<!-- EVA-ICONS JS -->
<script src="{{url('Admin')}}/assets/iconfonts/eva.min.js"></script>

<!-- CUSTOM SCROLLBAR JS-->
<script src="{{url('Admin')}}/assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>


<!-- CUSTOM JS -->
<script src="{{url('Admin')}}/assets/js/custom.js"></script>

<script type="text/javascript" src="{{url('Admin')}}/assets/plugins/toastify/toastify.js"></script>

@include('layouts.admin.inc.toaster')

@include('layouts.admin.inc.my-form')

</body>

</html>
