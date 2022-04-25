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
            <a href="{{url('/donotezzycaretouch/healthcare/user')}}">
                <div class="card d-card-part bg-warning mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="dripicons-heart float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Health Care Providers</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['healthcare']) ? $data['healthcare'] : '0'}}</span></h6>
                                <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_healthcare']) ? $data['today_healthcare'] : '0'}}</span> </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{url('/donotezzycaretouch/pharmacy/user')}}">
                <div class="card d-card-part bg-secondary mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="dripicons-box float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Pharmacy</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['pharmacist']) ? $data['pharmacist'] : '0'}}</span></h6>
                                <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_pharmacist']) ? $data['today_pharmacist'] : '0'}}</span> </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{url('/donotezzycaretouch/laboratories/user')}}">
                <div class="card d-card-part bg-success mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="dripicons-medical float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Laboratories</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['laboratories']) ? $data['laboratories'] : '0'}}</span></h6>
                                <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_laboratories']) ? $data['today_laboratories'] : '0'}}</span> </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{url('/donotezzycaretouch/customer/patient')}}">
                <div class="card d-card-part bg-danger mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="dripicons-document float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Patients</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['patient']) ? $data['patient'] : '0'}}</span></h6>
                                <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['today_patient']) ? $data['today_patient'] : '0'}}</span> </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6">
            <a href="{{url('/donotezzycaretouch/appointment')}}">
                <div class="card d-card-part bg-info mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="dripicons-clipboard float-right mb-0"></i>
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
        <div class="col-xl-3 col-md-6">
            <a href="{{url('/donotezzycaretouch/pharmacy/order')}}">
                <div class="card d-card-part bg-violet mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="mdi mdi-cart-outline float-right mb-0"></i>
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

        <div class="col-xl-3 col-md-6">
                <div class="card d-card-part bg-primary mini-stat m-b-30">
                    <div class="card-d-title text-white">
                        <div class="mini-stat-icon">
                            <i class="mdi mdi-account float-right mb-0"></i>
                        </div>
                        <h6 class="mb-0">Manage Appointment</h6>
                    </div>
                    <div class="card-body d-card-body">
                        <div class="mt-2 text-muted">
                            <div class="d-flex justify-content-between">
                                <a href="{{url('/donotezzycaretouch/appointment')}}">
                                    <h6>Completed <span class="d-block mb-1 d-number-count">{{ isset($data['completed_appointments']) ? $data['completed_appointments'] : '0'}}</span></h6>
                                </a>
                                <a href="{{url('/donotezzycaretouch/appointment')}}">
                                    <h6>Pending <span class="d-block mb-1 d-number-count">{{ isset($data['pending_appointments']) ? $data['pending_appointments'] : '0'}}</span> </h6>
                                </a>
                                <a href="{{url('/donotezzycaretouch/appointment/cancel')}}">
                                    <h6>Cancel <span class="d-block mb-1 d-number-count">{{ isset($data['cancel_appointments']) ? $data['cancel_appointments'] : '0'}}</span> </h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card d-card-part bg-dark mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="mdi mdi-account float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Manage Order</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <a href="{{url('/donotezzycaretouch/pharmacy/order')}}">
                                <h6>Completed <span class="d-block mb-1 d-number-count">{{ isset($data['completed_orders']) ? $data['completed_orders'] : '0'}}</span></h6>
                            </a>
                            <a href="{{url('/donotezzycaretouch/pharmacy/order')}}">
                                <h6>Pending <span class="d-block mb-1 d-number-count">{{ isset($data['pending_orders']) ? $data['pending_orders'] : '0'}}</span> </h6>
                            </a>
                            <a href="{{url('/donotezzycaretouch/pharmacy/order')}}">
                                <h6>Cancel <span class="d-block mb-1 d-number-count">{{ isset($data['cancel_orders']) ? $data['cancel_orders'] : '0'}}</span> </h6>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-md-6">
            <div class="card d-card-part bg-primary mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-document float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Patient Wallet</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['patient_wallet_total']) ? $data['patient_wallet_total'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['patient_wallet_today']) ? $data['patient_wallet_today'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card d-card-part bg-secondary mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-document float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">HCP Wallet</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['hcp_wallet_total']) ? $data['hcp_wallet_total'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">{{ isset($data['hcp_wallet_today']) ? $data['hcp_wallet_today'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card d-card-part bg-success mini-stat m-b-30">
                <div class="card-d-title text-white">
                    <div class="mini-stat-icon">
                        <i class="dripicons-document float-right mb-0"></i>
                    </div>
                    <h6 class="mb-0">Total Withdraw</h6>
                </div>
                <div class="card-body d-card-body">
                    <div class="mt-2 text-muted">
                        <div class="d-flex justify-content-between">
                            <h6>Pending <span class="d-block mb-1 d-number-count">{{ isset($data['withdraw_pending']) ? $data['withdraw_pending'] : '0'}}</span></h6>
                            <h6>In Progress <span class="d-block mb-1 d-number-count">{{ isset($data['withdraw_inprogress']) ? $data['withdraw_inprogress'] : '0'}}</span></h6>
                            <h6>Confirmed <span class="d-block mb-1 d-number-count">{{ isset($data['withdraw_confirmed']) ? $data['withdraw_confirmed'] : '0'}}</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="card-header-flex">
                        <h4 class="mt-0 header-title">Revenue</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" name="count_date_range" id="count-chart-date-range"  />
                            <input type="hidden" class="form-control" id="count_start_date" name="start_date" />
                            <input type="hidden" class="form-control" id="count_end_date" name="end_date"  />
                        </div>
                    </div>
                    <div id="morris-count-area-chart" style="height: 300px"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="card-header-flex">
                        <h4 class="mt-0 header-title">Revenue</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" name="revenue_date_range" id="revenue-chart-date-range"  />
                            <input type="hidden" class="form-control" id="revenue_start_date" name="start_date" />
                            <input type="hidden" class="form-control" id="revenue_end_date" name="end_date"  />
                        </div>
                    </div>   
                    <ul class="list-inline widget-chart m-t-20 text-center">
                        <li>
                            <h4 class="">{{isset($currency_symbol) ? $currency_symbol : ''}}<span id="total_income">0</span></h4>
                            <p class="text-muted m-b-0">Total Income</p>
                        </li>
                        <li>
                            <h4 class="">{{isset($currency_symbol) ? $currency_symbol : ''}} <span id="total_payout">0</span></h4>
                            <p class="text-muted m-b-0">Total Payout</p>
                        </li>
                    </ul>
                    <div id="morris-revenue-bar-chart" style="height: 195px"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-5">
            <div class="card m-b-30">
                <div class="card-body">
                    <div class="card-header-flex">
                        <h4 class="mt-0 header-title mb-4">Earning</h4>
                    </div>
                    <div class="card-header-flex">
                        @if(!empty($categories) && count($categories) > 0)
                            <div class="form-group">
                                <select id="searchByHcpTypeEarning" name="category_id" class="form-control">
                                    <option value=''>Select Hcp Type</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>  
                            </div>
                        @endif
                        <div class="form-group">
                            <input type="text" class="form-control" name="earning_date_range" id="earning-chart-date-range"  />
                            <input type="hidden" class="form-control" id="earning_start_date" name="start_date" />
                            <input type="hidden" class="form-control" id="earning_end_date" name="end_date"  />
                        </div>
                    </div>
                    <div class="">
                        <div class="row align-items-center mb-5">
                            <div class="col-md-6">
                                <div class="pl-3">
                                    <h3>{{isset($currency_symbol) ? $currency_symbol : ''}} <span id="ezzycare_earning">0</span></h3>
                                    <h6>EzzyCare Earning</h6>
                                    <!-- <p class="text-muted">Sed ut perspiciatis unde omnis</p> -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-center">
                                    <span class="peity-pie" id="appointments_order_treatment_earning" data-peity='{ "fill": ["#508aeb", "#f2f2f2"]}' data-width="84" data-height="84">0/0</span>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-4">
                                <div>
                                    <div class="mb-4">
                                        <span class="peity-donut appointments_earning" id="appointments_earning" data-peity='{ "fill": ["#508aeb", "#f2f2f2"], "innerRadius": 22, "radius": 32 }' data-width="60" data-height="60">0,0</span>
                                    </div>
                                    <h4><span id="appointments_percentage">0</span>%</h4>
                                    <p class="mb-0 text-muted">Appointment Earning</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div>
                                    <div class="mb-4">
                                        <span class="peity-donut" id="orders_earning" data-peity='{ "fill": ["#508aeb", "#f2f2f2"], "innerRadius": 22, "radius": 32 }' data-width="60" data-height="60">0,0</span>
                                    </div>
                                    <h4><span id="orders_percentage">0</span>%</h4>
                                    <p class="text-muted mb-0">Order Earning</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div>
                                    <div class="mb-4">
                                        <span class="peity-donut" id="treatment_plan_earning" data-peity='{ "fill": ["#508aeb", "#f2f2f2"], "innerRadius": 22, "radius": 32 }' data-width="60" data-height="60">0,0</span>
                                    </div>
                                    <h4><span id="treatment_plan_percentage">0</span>%</h4>
                                    <p class="text-muted mb-0">Treatment Plan Earning</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
            <div class="card m-b-30">
                <div class="card-body">
                    
                    <h4 class="mt-0 header-title mb-4">Payout</h4>

                    <div class="d-flex justify-content-around mb-3">
                        <a href="{{url('/donotezzycaretouch/payout/pending')}}" class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">{{ isset($data['pending_payout']) ? $data['pending_payout'] : '0'}} </h4>
                            <p class="text-white">Pending Payout</p>
                        </a>
                        <a href="{{url('/donotezzycaretouch/payout')}}" class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">{{ isset($data['approved_payout']) ? $data['approved_payout'] : '0'}} </h4>
                            <p class="text-white">Approved Payout</p>
                        </a>
                    </div>

                    <h4 class="mt-0 header-title mb-4"> Manage Pharmacy </h4>
                    <div class="d-flex justify-content-around">
                        <a href="{{url('/donotezzycaretouch/medicine/categories')}}" class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">{{ isset($data['medicine_categories']) ? $data['medicine_categories'] : '0'}} </h4>
                            <p class="text-white">Medicine Categories</p>
                        </a>
                        <a href="{{url('/donotezzycaretouch/medicine/details')}}" class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">{{ isset($data['medicine_details']) ? $data['medicine_details'] : '0'}} </h4>
                            <p class="text-white">Medicine Details</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            @can('appointments-list')
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">HCP Appointments</h4>
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
            @endcan
            @can('order-list')
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Pharmacy Orders</h4>
                    <div class="table-responsive">
                        <table id="pharmacy_order_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>User Name</th>
                                    <th>Service Provider Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            @endcan
            @can('appointments-list')
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Laboratories Appointments</h4>
                    <div class="table-responsive">
                        <table id="laboratories_appointments_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
            @endcan
        </div>
    </div>

</div><!-- container fluid End -->

@endsection
@section('script')
<script>
    var appointment_url = "{{url('/donotezzycaretouch/appointment')}}";
    var pharmacy_order_url = "{{url('/donotezzycaretouch/pharmacy/order')}}";
    var dashboard_url = "{{url('/donotezzycaretouch/')}}";
    var appointment_obj = {'status': '' };
    var pharmacy_order_obj = {'status': ['3','4'] };
    $('#start_date').val(moment().subtract(30, 'days').format("YYYY-MM-DD"));
    $('#end_date').val(moment().format("YYYY-MM-DD"));
</script>
<script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection