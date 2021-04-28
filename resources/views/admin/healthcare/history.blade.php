@extends('layouts.backend')

@section('title','User History')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/healthcare/user')}}">Health Care Providers</a></li>
                    <li class="breadcrumb-item active">History</li>
                </ol>
            </div>
            <h5 class="page-title">User History</h5>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card d-card-part bg-danger mini-stat m-b-30">
                                <div class="card-d-title text-white">
                                    <div class="mini-stat-icon">
                                        <i class="dripicons-clipboard float-right mb-0"></i>
                                    </div>
                                    <h6 class="mb-0">Manage Appointment</h6>
                                </div>
                                <div class="card-body d-card-body">
                                    <div class="mt-2 text-muted">
                                        <div class="d-flex justify-content-between">
                                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['appointments']) ? $data['appointments'] : '0'}}</span></h6>
                                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_patient']) ? $data['today_patient'] : '0'}}</span> </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card d-card-part bg-primary mini-stat m-b-30">
                                <div class="card-d-title text-white">
                                    <div class="mini-stat-icon">
                                        <i class="dripicons-clipboard float-right mb-0"></i>
                                    </div>
                                    <h6 class="mb-0">Manage Appointment</h6>
                                </div>
                                <div class="card-body d-card-body">
                                    <div class="mt-2 text-muted">
                                        <div class="d-flex justify-content-between">
                                            <h6>Completed <span class="d-block mb-1 d-number-count">{{ isset($data['completed_appointments']) ? $data['completed_appointments'] : '0'}}</span></h6>
                                            <h6>Pending <span class="d-block mb-1 d-number-count">{{ isset($data['upcoming_appointments']) ? $data['upcoming_appointments'] : '0'}}</span> </h6>
                                            <h6>Cancelled <span class="d-block mb-1 d-number-count">{{ isset($data['cancel_appointments']) ? $data['cancel_appointments'] : '0'}}</span> </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card d-card-part bg-dark mini-stat m-b-30">
                                <div class="card-d-title text-white">
                                    <div class="mini-stat-icon">
                                        <i class="dripicons-clipboard float-right mb-0"></i>
                                    </div>
                                    <h6 class="mb-0">Appointment Type</h6>
                                </div>
                                <div class="card-body d-card-body">
                                    <div class="mt-2 text-muted">
                                        <div class="d-flex justify-content-between">
                                            <h6>Clinic <span class="d-block mb-1 d-number-count">{{ isset($data['clinic_appointments']) ? $data['clinic_appointments'] : '0'}}</span></h6>
                                            <h6>Home <span class="d-block mb-1 d-number-count">{{ isset($data['home_appointments']) ? $data['home_appointments'] : '0'}}</span> </h6>
                                            <h6>Video <span class="d-block mb-1 d-number-count">{{ isset($data['video_appointments']) ? $data['video_appointments'] : '0'}}</span> </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!empty($user) && $user->category_id == '4')
                        <div class="col-xl-3 col-md-6">
                            <div class="card d-card-part bg-success mini-stat m-b-30">
                                <div class="card-d-title text-white">
                                    <div class="mini-stat-icon">
                                        <i class="dripicons-clipboard float-right mb-0"></i>
                                    </div>
                                    <h6 class="mb-0">Appointment</h6>
                                </div>
                                <div class="card-body d-card-body">
                                    <div class="mt-2 text-muted">
                                        <div class="d-flex justify-content-between">
                                            <h6>Urgent <span class="d-block mb-1 d-number-count">{{ isset($data['urgent_appointments']) ? $data['urgent_appointments'] : '0'}}</span></h6>
                                            <h6>Non Urgent <span class="d-block mb-1 d-number-count">{{ isset($data['nonurgent_appointments']) ? $data['nonurgent_appointments'] : '0'}}</span> </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Status</label>
                                    <select id="searchByStatus" name="status" class="form-control">
                                        <option value=''>Select Status</option>
                                        @foreach($statuses as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>       
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
    var data_status = '';
    var data_user_id = '{{$id}}';
    var data_urgent = '';
</script>
<script src="{{ asset('js/admin/appointment.js') }}" ></script>
@endsection