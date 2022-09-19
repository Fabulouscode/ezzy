@extends('layouts.backend')

@section('title','Appointment Reviews')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Appointment Reviews</li>
                </ol>
            </div>
            <h5 class="page-title">Appointments Reviews</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="appointment_review_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Appointment No</th>
                                    <th>User Name</th>
                                    <th>Patient Name</th>
                                    <th>Rating</th>
                                    <th>Reviews</th>
                                    <th>Happy Client</th>
                                </tr>
                            </thead>                        
                        </table>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<!-- container fluid End -->
@endsection

@section('script')
<script>
    var appointment_url = "{{url('/donotezzycaretouch/appointment')}}";
    var data_obj = {};
    var data_status = '';
    var data_user_id = '';
    var data_urgent = '';
</script>
 <!-- Bootstrap rating js -->
<script src="{{ asset('admin/plugins/bootstrap-rating/bootstrap-rating.min.js') }}"></script>
<script src="{{ asset('js/admin/appointment.js') }}" ></script>
@endsection