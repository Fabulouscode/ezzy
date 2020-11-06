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
                            <h6>Today <span class="d-block mb-1 d-number-count">0</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
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
                            <h6>Today <span class="d-block mb-1 d-number-count">0</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
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
                            <h6>Today <span class="d-block mb-1 d-number-count">0</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
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
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['laboratories']) ? $data['laboratories'] : '0'}}</span></h6>
                            <h6>Today <span class="d-block mb-1 d-number-count">0</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
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
                            <h6>Today <span class="d-block mb-1 d-number-count">0</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
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
                            <h6>Today <span class="d-block mb-1 d-number-count">0</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
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
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['appointments']) ? $data['appointments'] : '0'}}</span></h6>
                            <h6>Pending Completed <span class="d-block mb-1 d-number-count">0</span> </h6>
                        </div>
                    </div>
                </div>
            </div>
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
                            <h6>Total <span class="d-block mb-1 d-number-count">{{ isset($data['orders']) ? $data['orders'] : '0'}}</span></h6>
                            <h6>Pending Delivery <span class="d-block mb-1 d-number-count">5</span> </h6>
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
                    <h4 class="mt-0 header-title">Revenue</h4>

                    {{-- <ul class="list-inline widget-chart m-t-20 text-center">
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
                    </ul> --}}

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
                            <h4 class="">321</h4>
                            <p class="text-muted m-b-0">Total Income</p>
                        </li>
                        <li>
                            <h4 class="">964</h4>
                            <p class="text-muted m-b-0">Total Payout</p>
                        </li>
                    </ul>
                    <div id="morris-bar-example" style="height: 195px"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-5">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Earning</h4>
                    <div class="">
                        <div class="row align-items-center mb-5">
                            <div class="col-md-6">
                                <div class="pl-3">
                                    <h3>$6451</h3>
                                    <h6>Monthly Earning</h6>
                                    <p class="text-muted">Sed ut perspiciatis unde omnis</p>
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

        <div class="col-xl-7">
            <div class="card m-b-30">
                <div class="card-body">
                    
                    <h4 class="mt-0 header-title mb-4">Payout</h4>

                    <div class="d-flex justify-content-around mb-3">
                        <div class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">8 </h4>
                            <p class="text-white">Pending Payout</p>
                        </div>
                        <div class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">2 </h4>
                            <p class="text-white">Approved Payout</p>
                        </div>
                    </div>

                    <h4 class="mt-0 header-title mb-4"> Manage Pharmacy </h4>
                    <div class="d-flex justify-content-around">
                        <div class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">8 </h4>
                            <p class="text-white">Medicine Categories</p>
                        </div>
                        <div class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">2 </h4>
                            <p class="text-white">Medicine Subcategories</p>
                        </div>
                    </div>
                    {{-- <h4 class="mt-0 header-title mb-4">Manage Appointment</h4>

                    <div class="d-flex justify-content-around mb-3">
                        <div class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">8 </h4>
                            <p class="text-white">Pending Completed</p>
                        </div>
                        <div class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">2 </h4>
                            <p class="text-white">Completed</p>
                        </div>
                    </div>

                    <h4 class="mt-0 header-title mb-4">Manage Order</h4>
                    <div class="d-flex justify-content-around">
                        <div class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">8 </h4>
                            <p class="text-white">Pending Delivery</p>
                        </div>
                        <div class="small-box bg-info text-center px-5 py-4 mx-3">
                            <h4 class="text-white mb-1 font-weight-bold">2 </h4>
                            <p class="text-white">Completed Delivery</p>
                        </div>
                    </div> --}}

                    <div class="table-responsive d-none">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Age</th>
                                    <th>Start date</th>
                                </tr>

                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tiger Nixon</td>
                                    <td>System Architect</td>
                                    <td>61</td>
                                    <td>2017/04/25</td>
                                </tr>
                                <tr>
                                    <td>Garrett Winters</td>
                                    <td>Accountant</td>
                                    <td>63</td>
                                    <td>2017/07/25</td>
                                </tr>
                                <tr>
                                    <td>Ashton Cox</td>
                                    <td>Junior Technical Author</td>
                                    <td>66</td>
                                    <td>2015/01/12</td>
                                </tr>
                                <tr>
                                    <td>Cedric Kelly</td>
                                    <td>Senior Javascript Developer</td>
                                    <td>22</td>
                                    <td>2018/03/29</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Appointments</h4>
                    <div class="table-responsive">
                        <table id="appointmentsDatatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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

                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>patient patient</td>
                                <td>doctor doctor</td>
                                <td><div class="text-success"><strong>Doctor</strong></div><div class="text-success"><strong>Sickle Cell (Haematologist)</strong></div></td>
                                <td><div class="text-info"><strong>Video Call</strong></div></td>
                                <td>2020-02-04</td>
                                <td style=""><div class="text-success"><strong>Success</strong></div></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info" title="View"><i class="fa fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-info" title="Invoice"><i class="fa fa-file"></i></a>
                                </td>
                            </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Orders</h4>
                    <div class="table-responsive">
                        <table id="laboratoriesDatatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Mobile No.</th>
                                <th>HCP Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>patient</td>
                                <td>doctor@gmail.com</td>
                                <td><div>+58744155885</div></td>
                                <td><div class="text-success"><strong>Doctor</strong></div><div class="text-success"><strong>Sickle Cell (Haematologist)</strong></div></td>
                                <td style=""><div class="text-success"><strong>Success</strong></div></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info" title="View"><i class="fa fa-eye"></i></a>
                                    <a href="#" class="btn btn-sm btn-info" title="Invoice"><i class="fa fa-file"></i></a>
                                </td>
                            </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>s
        </div>
    </div>

</div><!-- container fluid End -->

@endsection
@section('script')
<script src="{{ asset('admin/pages/dashboard.js') }}"></script>
<script>
     $('#appointmentsDatatable').DataTable();
     $('#laboratoriesDatatable').DataTable();
</script>
@endsection