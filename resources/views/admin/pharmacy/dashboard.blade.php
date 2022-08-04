@extends('layouts.backend')

@section('title','Pharmacy Dashboard')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Main Dashboard</a></li>
                    <li class="breadcrumb-item active">Pharmacy Dashboard</li>
                </ol>
            </div>
            <h5 class="page-title">Pharmacy Dashboard</h5>
        </div>
    </div>
    <!-- start row --> 
    
    <div class="row">
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/donotezzycaretouch/pharmacy/user')}}">
            <div class="card d-card-part bg-primary mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-box float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Approved Pharmacy</h6>
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
            <a href="{{url('/donotezzycaretouch/pharmacy/user/pending')}}">
            <div class="card d-card-part bg-secondary mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-box float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Pending Pharmacy</h6>
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
            <a href="{{url('/donotezzycaretouch/pharmacy/order')}}">
            <div class="card d-card-part bg-violet mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-box float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Orders</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['orders']) ? $data['orders'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_orders']) ? $data['today_orders'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/donotezzycaretouch/pharmacy/order')}}">
            <div class="card d-card-part bg-info mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-box float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Completed Order</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['completed_orders']) ? $data['completed_orders'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_completed_orders']) ? $data['today_completed_orders'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/donotezzycaretouch/pharmacy/order')}}">
            <div class="card d-card-part bg-danger mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-box float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Pending Order</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['pending_orders']) ? $data['pending_orders'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_pending_orders']) ? $data['today_pending_orders'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-xl-4 col-md-6">
            <a href="{{url('/donotezzycaretouch/pharmacy/order')}}">
            <div class="card d-card-part bg-warning mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-box float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Cancel Order</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['cancel_orders']) ? $data['cancel_orders'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_cancel_orders']) ? $data['today_cancel_orders'] : '0'}}</span> </h6>
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