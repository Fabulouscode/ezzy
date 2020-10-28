<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>@yield('title')</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">

        <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" type="text/css">

    </head>


    <body class="fixed-left">
  
        <!-- Begin page -->
        @yield('content')   
        <!-- End page -->

        <!-- jQuery  -->
        <script src="asset('admin/js/jquery.min.js') }}"></script>
        <script src="asset('admin/js/bootstrap.bundle.min.js') }}"></script>
        <script src="asset('admin/js/modernizr.min.js') }}"></script>
        <script src="asset('admin/js/detect.js') }}"></script>
        <script src="asset('admin/js/fastclick.js') }}"></script>
        <script src="asset('admin/js/jquery.slimscroll.js') }}"></script>
        <script src="asset('admin/js/jquery.blockUI.js') }}"></script>
        <script src="asset('admin/js/waves.js') }}"></script>
        <script src="asset('admin/js/jquery.nicescroll.js') }}"></script>
        <script src="asset('admin/js/jquery.scrollTo.min.js') }}"></script>

        <!-- App js -->
        <script src="asset('admin/js/app.js') }}"></script>

    </body>
</html>

