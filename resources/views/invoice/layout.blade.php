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

   
        <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <!-- <link href="{{ asset('admin/css/icons.css') }}" rel="stylesheet" type="text/css"> -->
        <!-- <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" type="text/css"> -->
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


</body>
</html>
