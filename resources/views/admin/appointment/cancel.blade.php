@extends('layouts.backend')

@section('title','Appointments Canceled')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Appointments Canceled</li>
                </ol>
            </div>
            <h5 class="page-title">Appointments Canceled</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                
                    <!-- <div class="block-options-item mb-3 ml-3">
                        <a href="{{url('/category/create')}}" class="btn btn-info">Add Appointments</a>
                    </div> -->

                    <table id="appointments_datatable" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>User Name</th>
                                <th>Service Provider Name</th>
                                <th>HCP Type</th>
                                <th>Appointment Type</th>
                                <th>Start Date Time</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>                        
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<!-- container fluid End -->
@endsection

@section('script')
<script>
    var appointment_url = "{{url('/appointment')}}";
    var data_obj = {'status': 6 }
</script>
<script src="{{ asset('js/admin/appointment.js') }}" ></script>
@endsection