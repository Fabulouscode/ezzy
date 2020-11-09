@extends('layouts.backend')

@section('title','Laboratories Dashboard')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/')}}">Main Dashboard</a></li>
                    <li class="breadcrumb-item active">Laboratories Dashboard</li>
                </ol>
            </div>
            <h5 class="page-title">Laboratories Dashboard</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card d-card-part bg-primary mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-medical float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Approved Laboratories</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['approved_count']) ? $data['approved_count'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">0</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card d-card-part bg-info mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-medical float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Pending Laboratories</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['pending_count']) ? $data['pending_count'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">0</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-xl-3 col-md-6">
            <div class="card mini-stat m-b-30">
                <div class="p-3 bg-primary text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account-check float-right mb-0"></i>
                    </div>
                    <h6 class="text-uppercase mb-0">Approved Laboratories</h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['approved_count']) ? $data['approved_count'] : '0'}}<i class="mdi mdi-arrow-up text-success ml-2"></i></h5>                     
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
                    <h6 class="text-uppercase mb-0">Pending Laboratories</h6>
                </div>
                <div class="card-body">
                    <div class="mt-4 text-muted">
                        <h5 class="m-0">{{ isset($data['pending_count']) ? $data['pending_count'] : '0'}}<i class="mdi mdi-arrow-down text-danger ml-2"></i></h5>                        
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <!-- end row -->

</div><!-- container fluid End -->

@endsection
@section('script')
<!-- <script src="{{ asset('admin/pages/dashboard.js') }}"></script> -->
@endsection