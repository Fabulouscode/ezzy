@extends('layouts.backend')

@section('title','Health Care Providers Dashboard')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Main Dashboard</a></li>
                    <li class="breadcrumb-item active">Health Care Providers Dashboard</li>
                </ol>
            </div>
            <h5 class="page-title">Health Care Providers Dashboard</h5>
        </div>
    </div>
    <!-- end row -->
    
    <div class="row">

        <div class="col-xl-4 col-md-6">
            <a href="{{url('/healthcare/user')}}">
            <div class="card d-card-part bg-primary mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Approved Health Care Providers</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['approved_count']) ? $data['approved_count'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_approved_count']) ? $data['today_approved_count'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/healthcare/user/pending')}}">
            <div class="card d-card-part bg-secondary mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Pending Health Care Providers</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['pending_count']) ? $data['pending_count'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_pending_count']) ? $data['today_pending_count'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/appointment')}}">
            <div class="card d-card-part bg-violet mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Appointments</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['appointments']) ? $data['appointments'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_appointments']) ? $data['today_appointments'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/healthcare/user').'?hcp_type=Doctor'}}">
            <div class="card d-card-part bg-info mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Doctor</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['doctor']) ? $data['doctor'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_doctor']) ? $data['today_doctor'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/healthcare/user').'?hcp_type=Nurses'}}">
            <div class="card d-card-part bg-danger mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Nurses</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['nurses']) ? $data['nurses'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_nurses']) ? $data['today_nurses'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/healthcare/user').'?hcp_type=Massage Therapist'}}">
            <div class="card d-card-part bg-warning mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Massage Therapist</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['massage_therapist']) ? $data['massage_therapist'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_massage_therapist']) ? $data['today_massage_therapist'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </a>
    </div>
    <div class="row">
        
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/appointment')}}">
            <div class="card d-card-part bg-light-green mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Completed Appointment</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['completed_appointments']) ? $data['completed_appointments'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_completed_appointments']) ? $data['today_completed_appointments'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/appointment')}}">
            <div class="card d-card-part bg-success mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Upcoming Appointment</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['upcoming_appointments']) ? $data['upcoming_appointments'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_upcoming_appointments']) ? $data['today_upcoming_appointments'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/appointment/cancel')}}">
            <div class="card d-card-part bg-dark mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Cancel Appointment</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['cancel_appointments']) ? $data['cancel_appointments'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_cancel_appointments']) ? $data['today_cancel_appointments'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    <!-- end row -->

</div><!-- container fluid End -->

@endsection
@section('script')
<!-- <script src="{{ asset('admin/pages/dashboard.js') }}"></script> -->
@endsection