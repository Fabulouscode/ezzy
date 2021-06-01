@extends('layouts.backend')

@section('title','Laboratories Dashboard')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Main Dashboard</a></li>
                    <li class="breadcrumb-item active">Laboratories Dashboard</li>
                </ol>
            </div>
            <h5 class="page-title">Laboratories Dashboard</h5>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/laboratories/user')}}">
            <div class="card d-card-part bg-primary mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Approved Laboratories</h6>
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
            <a href="{{url('/laboratories/user/pending')}}">
            <div class="card d-card-part bg-secondary mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Pending Laboratories</h6>
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
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/laboratories/user').'?hcp_type=Pathologist'}}">
            <div class="card d-card-part bg-info mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Pathologist</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['pathologist']) ? $data['pathologist'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_pathologist']) ? $data['today_pathologist'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/laboratories/user').'?hcp_type=Scientists'}}">
            <div class="card d-card-part bg-danger mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Scientists</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['scientist']) ? $data['scientist'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_scientist']) ? $data['today_scientist'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/laboratories/user').'?hcp_type=Radiologist'}}">
            <div class="card d-card-part bg-warning mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-heart float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Radiologist (X-Ray & Scan)</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['radiologist']) ? $data['radiologist'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_radiologist']) ? $data['today_radiologist'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>       
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