@extends('layouts.backend')

@section('title','User History')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/pharmacy/dashboard')}}">Pharmacy Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/pharmacy/user')}}">Pharmacy</a></li>
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
                        <div class="col-xl-4 col-md-6">
                            <div class="card d-card-part bg-danger mini-stat m-b-30">
                                <div class="card-d-title text-white">
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-cart-outline float-right mb-0"></i>
                                    </div>
                                    <h6 class="mb-0">Manage Order</h6>
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
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card d-card-part bg-primary mini-stat m-b-30">
                                <div class="card-d-title text-white">
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-cart-outline float-right mb-0"></i>
                                    </div>
                                    <h6 class="mb-0">Manage Order</h6>
                                </div>
                                <div class="card-body d-card-body">
                                    <div class="mt-2 text-muted">
                                        <div class="d-flex justify-content-between">
                                            <h6>Completed <span class="d-block mb-1 d-number-count">{{ isset($data['completed_orders']) ? $data['completed_orders'] : '0'}}</span></h6>
                                            <h6>Pending <span class="d-block mb-1 d-number-count">{{ isset($data['cancel_orders']) ? $data['cancel_orders'] : '0'}}</span> </h6>
                                            <h6>Cancelled <span class="d-block mb-1 d-number-count">{{ isset($data['pending_orders']) ? $data['pending_orders'] : '0'}}</span> </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6">
                            <div class="card d-card-part bg-dark mini-stat m-b-30">
                                <div class="card-d-title text-white">
                                    <div class="mini-stat-icon">
                                        <i class="mdi mdi-cart-outline float-right mb-0"></i>
                                    </div>
                                    <h6 class="mb-0">Order Type</h6>
                                </div>
                                <div class="card-body d-card-body">
                                    <div class="mt-2 text-muted">
                                        <div class="d-flex justify-content-between">
                                            <h6>Home Delievry <span class="d-block mb-1 d-number-count">{{ isset($data['home_orders']) ? $data['home_orders'] : '0'}}</span></h6>
                                            <h6>Pick-up from Store <span class="d-block mb-1 d-number-count">{{ isset($data['pick_orders']) ? $data['pick_orders'] : '0'}}</span> </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <a href="{{url('/donotezzycaretouch/user/create')}}" class="btn btn-info">Add User</a>
                    </div> -->
                    <!-- Custom Filter -->
                    <div id="AdvanceFiletrShow" class="mb-4 ml-3 justify-content-start">
                        <label>Advanced Filter</label>
                        <div class="row mb-3">                               
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Date Range</label>
                                    <input type="text" class="form-control" name="date_range" id="order-date-range"  />
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
                        <table id="pharmacy_order_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>User Name</th>
                                    <th>Service Provider Name</th>
                                    <th>Created Date</th>
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
    var pharmacy_order_url = "{{url('/donotezzycaretouch/pharmacy/order')}}";
    var data_user_id = '{{$id}}';
</script>
<script src="{{ asset('js/admin/pharmacy_order.js') }}" ></script>
@endsection