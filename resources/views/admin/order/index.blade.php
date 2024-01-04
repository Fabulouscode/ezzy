@extends('layouts.backend')

@section('title','Orders List')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch/pharmacy/dashboard')}}">Pharmacy Dashboard</a></li>
                    <li class="breadcrumb-item active">Orders List</li>
                </ol>
            </div>
            <h5 class="page-title">Orders List</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                
                    <!-- <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/donotezzycaretouch/user/create')}}" class="btn btn-info">Add User</a>
                    </div> -->
                    <!-- Custom Filter -->
                    <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="javascript:void(0)" onclick="pharmacyOrderExportExcel()" class="btn btn-info">Export</a>
                    </div>
                    
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
    var pharmacy_order_export_url = "{{url('/donotezzycaretouch/pharmacy/order')}}";
    var data_user_id = '';
</script>
<script src="{{ asset('js/admin/pharmacy_order.js') }}" ></script>
@endsection