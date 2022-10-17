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
        <style>
            .w-5 {
            display: none;
        }
        </style>

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

        <nav class="navbar navbar-expand-md navbar-light navbar-area">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('frontend/img/logo.png') }}" class="img-fluid" alt="logo" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('doctors')}}" class="nav-link active">Our Care Providers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('happy_clients')}}">Happy Clients</a>
                        </li>
                    </ul>
                    <div class="navbar-btn">
                        <a href="#apps" class="download-appbtn">Download App</a>
                    </div>
                </div>
            </div>
        </nav>

        

        <section id="aboutus" class="why-choose pt-100 pb-100">
            <div class="container">
                <div class="row align-items-right">
                    <div class="col-xl-6 col-lg-6 col-md-6">
                        <div class="form-group">
                            <select name="search" id="search" class="form-control">
                                <option value="" >Select Category</option>
                                @foreach ($category as $key => $item)
                                    <option value={{$key}} >{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>                  
                <div class="row align-items-center" id="js_doctor_list">                    
                    @include('doctor_pagination')
                </div>
            </div>
        </section>

        

        <footer class="footer-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <div class="footer-widget">
                            <div class="footer-logo">
                                <img src="{{ asset('frontend/img/logo.png') }}" alt="logo" />
                            </div>
                            <p>Our goal is to be the best platform that connects patients to health care providers.</p>
                            <!-- <div class="footer-link">
                                <a href="#">
                                    <img src="{{ asset('frontend/img/google-play.png') }}" alt="google image" />
                                </a>
                                <a href="#">
                                    <img src="{{ asset('frontend/img/app-store.png') }}" alt="google image" />
                                </a>
                            </div> -->
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-6">
                        <div class="footer-widget pl-90">
                            <h3>Quick Links</h3>
                            <ul>
                                <li>
                                    <a href="#home">
                                        <i class="flaticon-right-arrow"></i>
                                        Home
                                    </a>
                                </li>
                                <li>
                                    <a href="#aboutus">
                                        <i class="flaticon-right-arrow"></i>
                                        About Us
                                    </a>
                                </li>
                                <li>
                                    <a href="#features">
                                        <i class="flaticon-right-arrow"></i>
                                        Features
                                    </a>
                                </li>
                                <li>
                                    <a href="#screenshots">
                                        <i class="flaticon-right-arrow"></i>
                                        Screenshots
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="footer-widget pl-90">
                            <h3>Social Pages</h3>
                            <ul>
                                <li>
                                    <a href="#">
                                        <i class="flaticon-right-arrow"></i>
                                        Facebook
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="flaticon-right-arrow"></i>
                                        Twitter
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="flaticon-right-arrow"></i>
                                        Linkedin
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="flaticon-right-arrow"></i>
                                        Instagram
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright-area">
                <div class="container">
                    <div class="row">
                        <div class="col-12 lh-1">
                            <p>&copy;2021 Ezzycare. All Rights Reserved.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

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

$('#js_doctor_list').on('click','a',function(e){
    e.preventDefault();
    let page_url = $(this).attr('href');
    let search = $("#search").val();
    $.ajax({
        type:"GET",
        url: page_url,
        data: {'search':search},
        dataType: 'html',
        success: function(response){
            $("#js_doctor_list").html(response)
        }
    });
});

$('#search').change(function(e){
    e.preventDefault();
    let page_url = "{{route('doctors')}}";
    let search = $(this).val();
    if (search != '') {
        $.ajax({
            type:"GET",
            url: page_url,
            data: {'search':search},
            dataType: 'html',
            success: function(response){
                $("#js_doctor_list").html(response)
            }
        });        
    }
});

</script>
    </body>
</html>
