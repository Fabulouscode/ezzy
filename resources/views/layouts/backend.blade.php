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

        <!--Morris Chart CSS -->
        <link rel="stylesheet" href="{{ asset('admin/plugins/morris/morris.css') }}">

        <!--Datatable CSS -->
        <link href="{{ asset('admin/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        
        <!-- Responsive datatable examples -->
        <link href="{{ asset('admin/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        
        <!-- Sweet Alert -->
        <link href="{{ asset('admin/plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
        
        <!-- Summernote css -->
        <link href="{{ asset('admin/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet" />

        <!-- Toaster Msg -->
        <link href="{{ asset('css/toastr.css') }}" rel="stylesheet" type="text/css">

        <!-- dropzone -->
        <link href="{{ asset('admin/plugins/dropzone/dist/dropzone.css') }}" rel="stylesheet" type="text/css">

        <!-- jquery-ui -->
        <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet" type="text/css">
        
        <!-- Bootstrap rating css -->
        <link href="{{ asset('admin/plugins/bootstrap-rating/bootstrap-rating.css') }}" rel="stylesheet" type="text/css">

        <!-- Bootstrap datepicker -->
        <link href="{{ asset('admin/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
        <!-- <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" /> -->
        <link href="{{ asset('admin/css/daterangepicker.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/css/jquery.mCustomScrollbar.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/css/icons.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet" type="text/css">

        <link href="{{ asset('admin/css/custom.css') }}" rel="stylesheet" type="text/css">

    @stack('css')
    </head>
    <body class="fixed-left">

        <!-- Loader -->
        <div id="preloader"><div id="status"><div class="spinner"></div></div></div>

        <!-- Begin page -->
        <div id="wrapper">
            <!-- Sidebar -->
            @include('layouts.includes.sidebar')
            <!-- END Sidebar -->

            <div class="content-page">
                <div class="content">
                    <!-- Header -->
                    @include('layouts.includes.header')
                    <!-- END Header -->

                    <!-- Main Container -->
                    <div class="page-content-wrapper ">
                    <!-- Page Content -->
                    @yield('content')
                    <!-- END Page Content -->
                    </div>
                    <!-- END Main Container -->

                </div> <!-- content -->

                <!-- Footer -->
                @include('layouts.includes.footer')
                <!-- END Footer -->

            </div>
            <!-- End Right content here -->
        </div>
        <!-- END wrapper -->

        <!-- jQuery  -->
        <script>
            var App_name_global =  "{{ config('app.name', 'Laravel') }}";
        </script>
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

        <!-- Toaster Msg -->
        <script src="{{ asset('js/toastr.js') }}" ></script>
    
        <!-- Dropzone Image Upoload -->
        <script src="{{ asset('admin/plugins/dropzone/dist/dropzone.js') }}" ></script>
 
        <!-- jquery-ui -->
        <script src="{{ asset('js/jquery-ui.js') }}" ></script>

        <!-- skycons -->
        <script src="{{ asset('admin/plugins/skycons/skycons.min.js') }}"></script>

        <!-- skycons -->
        <script src="{{ asset('admin/plugins/peity/jquery.peity.min.js') }}"></script>

        <!--Morris Chart-->
        <script src="{{ asset('admin/plugins/morris/morris.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/raphael/raphael-min.js') }}"></script>


        <!-- Required datatable js -->
        <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Buttons examples -->
        <script src="{{ asset('admin/plugins/datatables/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/datatables/jszip.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/datatables/pdfmake.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/datatables/vfs_fonts.js') }}"></script>
        <script src="{{ asset('admin/plugins/datatables/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/datatables/buttons.print.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/datatables/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('js/buttons.server-side.js') }}"></script>


        <!--Summernote js-->
        <script src="{{ asset('admin/plugins/summernote/summernote-bs4.min.js') }}"></script>

        <!-- Responsive examples -->
        <script src="{{ asset('admin/plugins/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

        <!-- Form validate js -->        
        <script src="{{ asset('admin/plugins/parsleyjs/parsley.min.js') }}"></script>

        <!-- Sweet-Alert  -->
        <script src="{{ asset('admin/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
        
        <!-- Bootstrap rating js -->
        <script src="{{ asset('admin/plugins/bootstrap-rating/bootstrap-rating.min.js') }}"></script>
        <script src="{{ asset('admin/js/jquery.mCustomScrollbar.js') }}"></script>
        
        <!-- Bootstrap datepicker -->
        <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
        <script src="{{ asset('admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <!-- <script type="text/javascript" src="{{ asset('js/daterangepicker.js') }}"></script> -->
        <script type="text/javascript" src="{{ asset('admin/js/daterangepicker.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('admin/js/app.js') }}"></script>

<script>
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
    getSupportPendingTicket();
    getAppointmentPendingCount();
});

function getSupportPendingTicket() {
     $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: "{{route('support_request_pending_count')}}",
        type: "get",
        dataType: 'json',
        success: function (data) {
            $('#SupportPendingTicketCount').text(data.data);
        },
        error: function (error) {

        }
    });
}

function getAppointmentPendingCount() {
     $.ajax({
        headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
        url: "{{route('appointments_pending_count')}}",
        type: "get",
        dataType: 'json',
        success: function (data) {
            $('#AppointmentPendingCount').text(data.data);
        },
        error: function (error) {

        }
    });
}


</script>
@yield('script')

</body>
</html>
