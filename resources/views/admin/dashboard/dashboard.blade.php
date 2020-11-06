@extends('layouts.backend')

@section('title','Dashboard')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <h5 class="page-title">Dashboard</h5>
        </div>
    </div>
    <div class="row">
         <div class="col-xl-3 col-md-6">
            <div class="card mini-stat m-b-30">
                <div class="p-3 bg-primary text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account float-right mb-0"></i>
                    </div>
                    <h6 class="text-uppercase mb-0">Health Care Providers</h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['healthcare']) ? $data['healthcare'] : '0'}}<i class="mdi mdi-arrow-up text-success ml-2"></i></h5>                     
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat m-b-30">
                <div class="p-3 bg-primary text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account float-right mb-0"></i>
                    </div>
                    <h6 class="text-uppercase mb-0">Pharmacy</h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['pharmacist']) ? $data['pharmacist'] : '0'}}<i class="mdi mdi-arrow-up text-success ml-2"></i></h5>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat m-b-30">
                <div class="p-3 bg-primary text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account float-right mb-0"></i>
                    </div>
                    <h6 class="text-uppercase mb-0">Laboratories</h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['laboratories']) ? $data['laboratories'] : '0'}}<i class="mdi mdi-arrow-up text-success ml-2"></i></h5>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat m-b-30">
                <div class="p-3 bg-primary text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account float-right mb-0"></i>
                    </div>
                    <h6 class="text-uppercase mb-0">Patients</h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['patient']) ? $data['patient'] : '0'}}<i class="mdi mdi-arrow-up text-success ml-2"></i></h5>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
         <div class="col-xl-3 col-md-6">
            <div class="card mini-stat m-b-30">
                <div class="p-3 bg-primary text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account float-right mb-0"></i>
                    </div>
                    <h6 class="text-uppercase mb-0">Appointments</h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['appointments']) ? $data['appointments'] : '0'}}<i class="mdi mdi-arrow-up text-success ml-2"></i></h5>                     
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat m-b-30">
                <div class="p-3 bg-primary text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account float-right mb-0"></i>
                    </div>
                    <h6 class="text-uppercase mb-0">Orders</h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['orders']) ? $data['orders'] : '0'}}<i class="mdi mdi-arrow-up text-success ml-2"></i></h5>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat m-b-30">
                <div class="p-3 bg-primary text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account float-right mb-0"></i>
                    </div>
                    <h6 class="text-uppercase mb-0">Manage Appointment</h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['orders']) ? $data['orders'] : '0'}}<i class="mdi mdi-arrow-up text-success ml-2"></i></h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stat m-b-30">
                <div class="p-3 bg-primary text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account float-right mb-0"></i>
                    </div>
                    <h6 class="text-uppercase mb-0">Manage Order </h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['orders']) ? $data['orders'] : '0'}}<i class="mdi mdi-arrow-up text-success ml-2"></i></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->

    <!-- end row -->
    <div class="row">
        <div class="col-xl-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Email Sent</h4>

                    <ul class="list-inline widget-chart m-t-20 text-center">
                        <li>
                            <h4 class=""><b>3652</b></h4>
                            <p class="text-muted m-b-0">Marketplace</p>
                        </li>
                        <li>
                            <h4 class=""><b>5421</b></h4>
                            <p class="text-muted m-b-0">Last week</p>
                        </li>
                        <li>
                            <h4 class=""><b>9652</b></h4>
                            <p class="text-muted m-b-0">Last Month</p>
                        </li>
                    </ul>

                    <div id="morris-area-example" style="height: 300px"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Revenue</h4>

                    <ul class="list-inline widget-chart m-t-20 text-center">
                        <li>
                            <h4 class=""><b>5248</b></h4>
                            <p class="text-muted m-b-0">Marketplace</p>
                        </li>
                        <li>
                            <h4 class=""><b>321</b></h4>
                            <p class="text-muted m-b-0">Last week</p>
                        </li>
                        <li>
                            <h4 class=""><b>964</b></h4>
                            <p class="text-muted m-b-0">Last Month</p>
                        </li>
                    </ul>
                    <div id="morris-bar-example" style="height: 300px"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-xl-4">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Monthly Earning</h4>
                    <div class="">
                        
                        <div class="row align-items-center mb-5">
                            <div class="col-md-6">
                                <div class="pl-3">
                                    <h3>$6451</h3>
                                    <h6>Monthly Earning</h6>
                                    <p class="text-muted">Sed ut perspiciatis unde omnis</p>
                                    <a href="#" class="text-primary">Learn more...</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <span class="peity-pie" data-peity='{ "fill": ["#508aeb", "#f2f2f2"]}' data-width="84" data-height="84">6/8</span>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <div>
                                    <div class="mb-4">
                                        <span class="peity-donut" data-peity='{ "fill": ["#508aeb", "#f2f2f2"], "innerRadius": 22, "radius": 32 }' data-width="60" data-height="60">2,4</span>
                                    </div>
                                    <h4>42%</h4>
                                    <p class="mb-0 text-muted">Online Earning</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <div class="mb-4">
                                        <span class="peity-donut" data-peity='{ "fill": ["#508aeb", "#f2f2f2"], "innerRadius": 22, "radius": 32 }' data-width="60" data-height="60">8,4</span>
                                    </div>
                                    <h4>58%</h4>
                                    <p class="text-muted mb-0">Offline Earning</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- container fluid End -->

@endsection
@section('script')
<script src="{{ asset('admin/pages/dashboard.js') }}"></script>
@endsection