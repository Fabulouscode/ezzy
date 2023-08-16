<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="shortcut icon" href="{{ asset('frontend/img/favicon.ico') }}" />
        <title>Ezzycare</title>

        <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/font/flaticon.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/fontawesome.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/owl.theme.default.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/slick-theme.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}" />
        <link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}" />
        <!-- Toaster Msg -->
        <link href="{{ asset('css/toastr.css') }}" rel="stylesheet" type="text/css">
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-BN9KQRLQL5"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-BN9KQRLQL5');
        </script>
    </head>
    <body data-spy="scroll" data-offset="70">
        <div class="loader-content">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="spinner">
                        <div class="double-bounce1"></div>
                        <div class="double-bounce2"></div>
                    </div>
                </div>
            </div>
        </div>
        @include('frontlayout.includes.header')
        

        @yield('content')


        @include('frontlayout.includes.footer')
        

        <!-- <div class="top-btn">
            <i class="flaticon-startup"></i>
        </div> -->
        <a href="#" class="top-btn"><i class="fa fa-angle-up"></i></a>
        <script>            
            var App_name_global =  "{{ config('app.name', 'Laravel') }}";
            var SITEURL = "{{URL::to('')}}";
        </script>
        <script src="{{ asset('frontend/js/jquery-2.2.4.min.js') }}"></script>
        <script src="{{ asset('frontend/js/popper.min.js') }}"></script>
        <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('frontend/js/jquery.ajaxchimp.min.js') }}"></script>
        <script src="{{ asset('frontend/js/form-validator.min.js') }}"></script>
        <script src="{{ asset('frontend/js/slick.min.js') }}"></script>
        <script src="{{ asset('frontend/js/jquery.mixitup.min.js') }}"></script>
        <script src="{{ asset('frontend/js/counter.min.js') }}"></script>
        <script src="{{ asset('frontend/js/waypoint.min.js') }}"></script>
        <script src="{{ asset('frontend/js/wow.min.js') }}"></script>
        <script src="{{ asset('frontend/js/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('admin/js/jquery.validate.js') }}"></script>
        <script src="{{ asset('frontend/js/custom.js') }}"></script>        
        <script src="{{ asset('frontend/js/contact-form-script.js') }}"></script>
        <!-- Toaster Msg -->
        <script src="{{ asset('js/toastr.js') }}" ></script>

<script type="text/javascript">
$(function () {
    toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
    }
});

</script>
@yield('script')
    </body>
</html>
