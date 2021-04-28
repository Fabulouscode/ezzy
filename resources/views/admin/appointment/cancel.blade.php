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
                
                    <!-- <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/category/create')}}" class="btn btn-info">Add Appointments</a>
                    </div> -->
                    <!-- Custom Filter -->
                    <div id="AdvanceFiletrShow" class="mb-4 ml-3 justify-content-start">
                        <label>Advanced Filter</label>
                        <div class="row mb-3">                       
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Hcp Type</label>
                                    <select id="searchByHcpType" name="category_id" class="form-control">
                                        <option value=''>Select Hcp Type</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>       
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Appointment Type</label>
                                    <select id="searchByAppointmentType" name="appointment_type" class="form-control">
                                        <option value=''>Select Appointment Type</option>
                                        <option value='0'>Clinic</option>
                                        <option value='1'>Home</option>
                                        <option value='2'>Video</option>
                                    </select>       
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Date Range</label>
                                    <input type="text" class="form-control" name="date_range" id="appointment-date-range"  />
                                    <input type="hidden" class="form-control" id="start_date" name="start_date" />
                                    <input type="hidden" class="form-control" id="end_date" name="end_date"  />     
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="appointments_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div>
<!-- container fluid End -->
@endsection

@section('script')
<script>
    var appointment_url = "{{url('/appointment')}}";
    var data_obj = {};
    var data_status = '6';
</script>
<script src="{{ asset('js/admin/appointment.js') }}" ></script>
@endsection