@extends('layouts.backend')

@section('title','Transaction List')

@section('content')
<!-- container fluid Start -->
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="float-right page-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{url('/donotezzycaretouch')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Transaction List</li>
                </ol>
            </div>
            <h5 class="page-title">Transaction List</h5>
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                
                    <!-- <div class="block-options-item mb-3 mr-3 float-right">
                        <a href="{{url('/donotezzycaretouch/services/create')}}" class="btn btn-info">Add Service</a>
                    </div> -->
                    <!-- Custom Filter -->
                    <div id="AdvanceFiletrShow" class="mb-4 ml-3 justify-content-start">
                        <label>Advanced Filter</label>
                        <div class="row mb-3">                       
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Hcp Type</label>
                                    <select id="searchByHcpTypeTransaction" name="category_id" class="form-control">
                                        <option value=''>Select Hcp Type</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>       
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div className="form-group">
                                    <label>Date Range</label>
                                    <input type="text" class="form-control" name="date_range" id="user-date-range"  />
                                    <input type="hidden" class="form-control" id="user_start_date" name="start_date" />
                                    <input type="hidden" class="form-control" id="user_end_date" name="end_date"  />     
                                </div>
                            </div>  
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="transaction_datatable" class="table ui-datatable table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>                                    
                                    <th>Id</th>   
                                    <th>Patient Name</th>   
                                    <th>Provider Name</th>
                                    <th>Transaction Msg</th>
                                    <th>Transaction Date</th>
                                    <th>EzzyCare Charge</th>
                                    <th>Provider Charge</th>
                                    <th>Total Charge</th>
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
    var payout_url = "{{url('/donotezzycaretouch')}}";
    var payout_obj = '';
    var payout_status = '';
    var payout_history = {};
</script>
<script src="{{ asset('js/admin/payout.js') }}" ></script>
@endsection