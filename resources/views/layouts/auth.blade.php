<!doctype html>
<html lang="en" class="no-focus">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <meta name="csrf-token" content="{{ csrf_token() }}">        
        <meta content="Admin Dashboard" name="description" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title>@yield('title','EzzyCare')</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">

   
        <link href="{{ asset('admin/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" type="text/css">
     <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    @stack('css')
    </head>
    <body class="fixed-left">

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>

        <!-- Begin page -->
        <div class="accountbg">
            
            <div class="content-center">
           
                <!-- Page Content -->
                @yield('content')
                <!-- END Page Content -->
           
            </div>
            
        </div>
        <!-- END page -->

        <!-- jQuery  -->
        <script src="{{ asset('admin/js/jquery.min.js') }}"></script>
        <script src="{{ asset('admin/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('admin/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('admin/js/detect.js') }}"></script>
        <script src="{{ asset('admin/js/fastclick.js') }}"></script>
        <script src="{{ asset('admin/js/jquery.slimscroll.js') }}"></script>
        <script src="{{ asset('admin/js/jquery.blockUI.js') }}"></script>
        <script src="{{ asset('admin/js/waves.js') }}"></script>
        <script src="{{ asset('admin/js/jquery.nicescroll.js') }}"></script>
        <script src="{{ asset('admin/js/jquery.scrollTo.min.js') }}"></script>
        <script src="{{ asset('admin/js/jquery.validate.js') }}"></script>
        <script src="{{ asset('admin/plugins/parsleyjs/parsley.min.js') }}"></script>
        
        <!-- skycons -->
        <script src="{{ asset('admin/plugins/skycons/skycons.min.js') }}"></script>

        <!-- skycons -->
        <script src="{{ asset('admin/plugins/peity/jquery.peity.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('admin/js/app.js') }}"></script>

@yield('script')
</body>
</html>
